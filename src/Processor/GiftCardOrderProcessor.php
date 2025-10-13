<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Processor;

use Macbim\SyliusGiftCardsPlugin\Factory\GiftCardAdjustmentFactoryInterface;
use Macbim\SyliusGiftCardsPlugin\Model\OrderInterface;
use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

final class GiftCardOrderProcessor implements OrderProcessorInterface
{
    public function __construct(
        private readonly GiftCardAdjustmentFactoryInterface $giftCardAdjustmentFactory,
    )
    {
    }

    public function process(BaseOrderInterface $order): void
    {
        if (!$order instanceof OrderInterface) {
            return;
        }

        foreach ($order->getAppliedGiftCards() as $giftCard) {
            $order->addAdjustment(
                $this->giftCardAdjustmentFactory->createNewForGiftCard($giftCard, $order),
            );
        }
    }
}
