<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Checker\GiftCard;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Model\OrderInterface;
use Symfony\Component\Clock\ClockInterface;

class GiftCardIsNotExpiredEligibilityChecker implements GiftCardEligibilityCheckerInterface
{
    public function __construct(
        private readonly ClockInterface $clock,
    )
    {
    }

    public function isEligible(GiftCardInterface $giftCard, OrderInterface $order): bool
    {
        if ($giftCard->getExpiresAt() === null) {
            return true;
        }

        return $giftCard->getExpiresAt() >= $this->clock->now();
    }
}
