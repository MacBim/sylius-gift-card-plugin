<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Twig\Runtime;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Model\OrderInterface;
use Macbim\SyliusGiftCardsPlugin\Repository\GiftCardRepositoryInterface;
use Twig\Extension\RuntimeExtensionInterface;

class GiftCardRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly GiftCardRepositoryInterface $giftCardRepository,
    ) {
    }

    /**
     * @return iterable<GiftCardInterface>
     */
    public function getGiftCardsCreatedByOrder(OrderInterface $order): iterable
    {
        return $this->giftCardRepository->findForOrder($order);
    }
}
