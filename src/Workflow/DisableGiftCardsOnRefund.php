<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Workflow;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Model\OrderInterface;
use Macbim\SyliusGiftCardsPlugin\Repository\GiftCardRepositoryInterface;
use Sylius\Component\Core\Model\OrderItemUnitInterface;

class DisableGiftCardsOnRefund
{
    public function __construct(
        private readonly GiftCardRepositoryInterface $giftCardRepository
    )
    {
    }

    public function __invoke(OrderInterface $order): void
    {
        // At this point, if we have a refund, the order has already been paid and fulfilled.
        // Which means that gift cards have already been created and linked to an OrderItemUnit.
        foreach ($order->getGiftCardsOrderItems() as $orderItem) {
            /** @var OrderItemUnitInterface $unit */
            foreach ($orderItem->getUnits() as $unit) {
                $giftCard = $this->giftCardRepository->findOneBy(['orderItemUnit' => $unit]);

                if ($giftCard instanceof GiftCardInterface) {
                    $giftCard->setEnabled(false);
                }
            }
        }
    }
}
