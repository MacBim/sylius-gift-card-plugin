<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Modifier\GiftCard;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;

class GiftCardAmountModifier implements GiftCardAmountModifierInterface
{
    public function decreaseAmount(GiftCardInterface $giftCard, int $amount): void
    {
        $newAmount = max(0, $giftCard->getAmount() - $amount);

        $giftCard->setAmount($newAmount);

        if (0 === $giftCard->getAmount()) {
            $giftCard->setEnabled(false);
        }
    }

    public function increaseAmount(GiftCardInterface $giftCard, int $amount): void
    {
        if (null === $giftCard->getInitialAmount()) {
            throw new \LogicException(sprintf('Gift card "%s" has no initial amount.', $giftCard->getCode()));
        }

        $amount = min($giftCard->getAmount() + $amount, $giftCard->getInitialAmount());

        $giftCard->setAmount($amount);
    }
}
