<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Api\Assigner;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Model\OrderInterface;
use Macbim\SyliusGiftCardsPlugin\Repository\GiftCardRepositoryInterface;
use Sylius\Bundle\ApiBundle\Assigner\OrderPromotionCodeAssignerInterface;
use Sylius\Component\Core\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

class GiftCardOrPromotionCouponAssigner implements OrderPromotionCodeAssignerInterface
{
    public function __construct(
        private readonly GiftCardRepositoryInterface         $giftCardRepository,
        private readonly OrderProcessorInterface             $orderProcessor,
        private readonly OrderPromotionCodeAssignerInterface $promotionCodeAssigner,
    )
    {
    }

    /**
     * @param OrderInterface $cart
     */
    public function assign(BaseOrderInterface $cart, ?string $couponCode = null): BaseOrderInterface
    {
        if ($couponCode !== null) {
            $giftCard = $this->giftCardRepository->findOneEnabledByCode($couponCode);
            if ($giftCard instanceof GiftCardInterface) {
                $cart->addAppliedGiftCard($giftCard);

                $this->orderProcessor->process($cart);

                return $cart;
            }
        }


        return $this->promotionCodeAssigner->assign($cart, $couponCode);
    }
}
