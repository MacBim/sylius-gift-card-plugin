<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Api\Validator;

use Macbim\SyliusGiftCardsPlugin\Checker\GiftCard\GiftCardEligibilityCheckerInterface;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Model\OrderInterface;
use Macbim\SyliusGiftCardsPlugin\Repository\GiftCardRepositoryInterface;
use Sylius\Bundle\ApiBundle\Command\Checkout\UpdateCart;
use Sylius\Bundle\ApiBundle\Validator\Constraints\PromotionCouponEligibility;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

class GiftCardOrPromotionCouponEligibilityValidator extends ConstraintValidator
{
    public function __construct(
        private readonly OrderRepositoryInterface            $orderRepository,
        private readonly GiftCardRepositoryInterface         $giftCardRepository,
        private readonly GiftCardEligibilityCheckerInterface $giftCardEligibilityChecker,
        private readonly ConstraintValidator                 $promotionCouponValidator,
    )
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        Assert::isInstanceOf($value, UpdateCart::class);

        Assert::isInstanceOf($constraint, PromotionCouponEligibility::class);

        if ($value->couponCode === null || $value->getOrderTokenValue() === null) {
            return;
        }

        /** @var OrderInterface $cart */
        $cart = $this->orderRepository->findCartByTokenValue($value->getOrderTokenValue());

        $giftCard = $this->giftCardRepository->findOneEnabledByCode($value->couponCode);
        if ($giftCard instanceof GiftCardInterface) {
            if (!$this->giftCardEligibilityChecker->isEligible($giftCard, $cart)) {
                $this->context
                    ->buildViolation('macbim_sylius_gift_cards.not_eligible')
                    ->atPath('couponCode')
                    ->addViolation();
            }

            return;
        }

        $this->promotionCouponValidator->validate($value, $constraint);
    }
}
