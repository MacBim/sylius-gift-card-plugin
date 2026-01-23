<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Provider;

use Doctrine\ORM\NonUniqueResultException;
use Macbim\SyliusGiftCardsPlugin\Exception\ChannelConfigurationNotFoundException;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCardChannelConfigurationInterface;
use Macbim\SyliusGiftCardsPlugin\Repository\GiftCardChannelConfigurationRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;

class GiftCardChannelConfigurationProvider implements GiftCardChannelConfigurationProviderInterface
{
    public function __construct(
        private readonly GiftCardChannelConfigurationRepositoryInterface $configurationRepository,
    ) {}

    /**
     * @throws ChannelConfigurationNotFoundException
     */
    public function provideConfigurationForChannel(ChannelInterface $channel): GiftCardChannelConfigurationInterface
    {
        /* @var GiftCardChannelConfigurationInterface|null $channelConfiguration */
        try {
            $channelConfiguration = $this->configurationRepository->findOneEnabledByChannel($channel);
        } catch (NonUniqueResultException $e) {
            throw new ChannelConfigurationNotFoundException(sprintf('Multiple enabled channel configurations found for channel "%s"', $channel->getCode()), previous: $e);
        }

        if (null === $channelConfiguration) {
            throw new ChannelConfigurationNotFoundException(sprintf('No configuration found for channel "%s"', $channel->getCode()));
        }

        return $channelConfiguration;
    }
}
