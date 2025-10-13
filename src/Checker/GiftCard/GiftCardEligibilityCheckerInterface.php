<?php

namespace Macbim\SyliusGiftCardsPlugin\Checker\GiftCard;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Model\OrderInterface;

interface GiftCardEligibilityCheckerInterface
{
    public function isEligible(GiftCardInterface $giftCard, OrderInterface $order): bool;
}
