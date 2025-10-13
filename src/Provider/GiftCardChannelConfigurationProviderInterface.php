<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Provider;

use Macbim\SyliusGiftCardsPlugin\Exception\ChannelConfigurationNotFoundException;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCardChannelConfigurationInterface;
use Sylius\Component\Core\Model\ChannelInterface;

interface GiftCardChannelConfigurationProviderInterface
{
    /**
     * @throws ChannelConfigurationNotFoundException
     */
    public function provideConfigurationForChannel(ChannelInterface $channel): GiftCardChannelConfigurationInterface;
}
