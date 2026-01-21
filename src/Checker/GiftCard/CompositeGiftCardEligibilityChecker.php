<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Checker\GiftCard;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Model\OrderInterface;

class CompositeGiftCardEligibilityChecker implements GiftCardEligibilityCheckerInterface
{
    /**
     * @param iterable<GiftCardEligibilityCheckerInterface> $checkers
     */
    public function __construct(
        private readonly iterable $checkers,
    ) {
    }

    public function isEligible(GiftCardInterface $giftCard, OrderInterface $order): bool
    {
        foreach ($this->checkers as $checker) {
            if (false === $checker->isEligible($giftCard, $order)) {
                return false;
            }
        }

        return true;
    }
}
