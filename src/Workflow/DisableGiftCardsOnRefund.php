<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Workflow;

use Macbim\SyliusGiftCardsPlugin\Model\OrderInterface;
use Macbim\SyliusGiftCardsPlugin\Repository\GiftCardRepositoryInterface;

final class DisableGiftCardsOnRefund
{
    public function __construct(
        private readonly GiftCardRepositoryInterface $giftCardRepository,
    ) {
    }

    public function __invoke(OrderInterface $order): void
    {
        // At this point, if we have a refund, the order has already been paid and fulfilled.
        // Which means that gift cards have already been created and linked to an OrderItemUnit.
        $giftCards = $this->giftCardRepository->findForOrder($order);

        foreach ($giftCards as $giftCard) {
            $giftCard->setEnabled(false);
        }
    }
}
