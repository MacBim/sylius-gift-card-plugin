<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Factory;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface GiftCardAdjustmentFactoryInterface
{
    public function createNewForGiftCard(GiftCardInterface $giftCard, OrderInterface $order): AdjustmentInterface;
}
