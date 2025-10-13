<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Modifier\GiftCard;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;

interface GiftCardAmountModifierInterface
{
    public function decreaseAmount(GiftCardInterface $giftCard, int $amount): void;

    public function increaseAmount(GiftCardInterface $giftCard, int $amount): void;
}
