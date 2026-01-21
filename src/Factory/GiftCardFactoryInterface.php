<?php

namespace Macbim\SyliusGiftCardsPlugin\Factory;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderItemUnitInterface;
use Sylius\Resource\Factory\FactoryInterface;

/**
 * @template-extends FactoryInterface<GiftCardInterface>
 */
interface GiftCardFactoryInterface extends FactoryInterface
{
    public function createNew(): GiftCardInterface;

    public function createNewForOrderItemUnit(OrderItemUnitInterface $orderItemUnit, ChannelInterface $channel, string $currencyCode): GiftCardInterface;

    public function createNewEnabledForOrderItemUnit(OrderItemUnitInterface $orderItemUnit, ChannelInterface $channel, string $currencyCode): GiftCardInterface;
}
