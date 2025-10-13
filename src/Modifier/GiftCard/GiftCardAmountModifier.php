<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Modifier\GiftCard;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;

class GiftCardAmountModifier implements GiftCardAmountModifierInterface
{
    public function decreaseAmount(GiftCardInterface $giftCard, int $amount): void
    {
        if ($amount >= $giftCard->getAmount()) {
            $giftCard->setEnabled(false);
            $giftCard->setAmount(0);
        }

        if ($amount < $giftCard->getAmount()) {
            $giftCard->setEnabled(true);

            $giftCard->setAmount($giftCard->getAmount() - $amount);
        }
    }

    public function increaseAmount(GiftCardInterface $giftCard, int $amount): void
    {
        $amount = min($giftCard->getAmount() + $amount, $giftCard->getInitialAmount());

        $giftCard->setAmount($amount);
    }
}
