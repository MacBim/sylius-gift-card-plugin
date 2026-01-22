<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Factory;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;

final class GiftCardAdjustmentFactory implements GiftCardAdjustmentFactoryInterface
{
    /**
     * @param AdjustmentFactoryInterface<AdjustmentInterface> $adjustmentFactory
     */
    public function __construct(
        private readonly AdjustmentFactoryInterface $adjustmentFactory,
    ) {}

    public function createNewForGiftCard(GiftCardInterface $giftCard, OrderInterface $order): AdjustmentInterface
    {
        // We do not want to apply a reduction greater than the order total amount
        $amount = min($giftCard->getAmount(), $order->getTotal());

        /** @var AdjustmentInterface $adjustment */
        $adjustment = $this->adjustmentFactory->createWithData(
            type: AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT,
            label: 'gift_card',
            amount: -$amount,
            details: [
                'amount' => $amount,
                'initial_amount' => $giftCard->getInitialAmount(),
                'code' => $giftCard->getCode(),
            ]
        );

        $adjustment->setOriginCode(GiftCardInterface::ADJUSTMENT_ORIGIN);

        return $adjustment;
    }
}
