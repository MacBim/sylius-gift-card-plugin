<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Provider;

use Macbim\SyliusGiftCardsPlugin\Exception\ChannelConfigurationNotFoundException;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCardChannelConfigurationInterface;
use Macbim\SyliusGiftCardsPlugin\Repository\GiftCardChannelConfigurationRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;

class CachedGiftChannelCardChannelConfigurationProvider implements GiftCardChannelConfigurationProviderInterface
{
    /** @var array<string, GiftCardChannelConfigurationInterface> */
    private array $configurationCache = [];

    public function __construct(
        private readonly GiftCardChannelConfigurationRepositoryInterface $configurationRepository,
    )
    {
    }

    /**
     * @throws ChannelConfigurationNotFoundException
     */
    public function provideConfigurationForChannel(ChannelInterface $channel): GiftCardChannelConfigurationInterface
    {
        $channelCode = $channel->getCode();

        if (!isset($this->configurationCache[$channelCode])) {
            $this->configurationCache[$channelCode] = $this->doProvideConfigurationForChannel($channel);
        }

        return $this->configurationCache[$channelCode];
    }

    /**
     * @throws ChannelConfigurationNotFoundException
     */
    private function doProvideConfigurationForChannel(ChannelInterface $channel): GiftCardChannelConfigurationInterface
    {
        /** @var GiftCardChannelConfigurationInterface|null $channelConfiguration */
        $channelConfiguration = $this->configurationRepository->findOneBy(['channel' => $channel]);
        if (null === $channelConfiguration) {
            throw new ChannelConfigurationNotFoundException(sprintf('No configuration found for channel "%s"', $channel->getCode()));
        }

        return $channelConfiguration;
    }
}
