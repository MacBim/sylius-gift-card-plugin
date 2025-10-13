<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Workflow;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Model\OrderInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;

abstract class AbstractAmountModifierWorkflow
{
    public function __invoke(OrderInterface $order): void
    {
        $appliedGiftCards = $this->sortAppliedGiftCardsByCode($order);
        if ($appliedGiftCards === []) {
            return;
        }

        /** @var AdjustmentInterface $adjustment */
        foreach ($order->getAdjustmentsRecursively(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT) as $adjustment) {
            if ($adjustment->getOriginCode() !== GiftCardInterface::ADJUSTMENT_ORIGIN) {
                continue;
            }

            if ($adjustment->getAmount() === 0) {
                continue;
            }

            /** @var array{amount: int, initial_amount: int, code: string} $details */
            $details = $adjustment->getDetails();
            if (!isset($appliedGiftCards[$details['code']])) {
                throw new \RuntimeException(
                    sprintf('Order has an adjustment for a gift card (%s) that was not applied', $details['code'])
                );
            }

            $giftCard = $appliedGiftCards[$details['code']];

            $amount = abs($adjustment->getAmount());

            $this->doModifyAmount($giftCard, $amount);
        }
    }

    abstract protected function doModifyAmount(GiftCardInterface $giftCard, int $amount): void;

    /**
     * @return array<string, GiftCardInterface>
     */
    protected function sortAppliedGiftCardsByCode(OrderInterface $order): array
    {
        $appliedGiftCards = [];

        foreach ($order->getAppliedGiftCards() as $giftCard) {
            $appliedGiftCards[$giftCard->getCode()] = $giftCard;
        }

        return $appliedGiftCards;
    }
}
