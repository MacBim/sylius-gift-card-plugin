<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Checker\GiftCard;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Model\OrderInterface;

class GiftCardEnabledEligibilityChecker implements GiftCardEligibilityCheckerInterface
{
    public function isEligible(GiftCardInterface $giftCard, OrderInterface $order): bool
    {
        return $giftCard->isEnabled();
    }
}
