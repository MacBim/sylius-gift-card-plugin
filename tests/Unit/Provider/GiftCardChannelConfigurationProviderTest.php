<?php

declare(strict_types=1);

namespace Unit\Provider;

use Doctrine\ORM\NonUniqueResultException;
use Macbim\SyliusGiftCardsPlugin\Exception\ChannelConfigurationNotFoundException;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCardChannelConfiguration;
use Macbim\SyliusGiftCardsPlugin\Provider\GiftCardChannelConfigurationProvider;
use Macbim\SyliusGiftCardsPlugin\Repository\GiftCardChannelConfigurationRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Channel;

#[CoversClass(GiftCardChannelConfigurationProvider::class)]
class GiftCardChannelConfigurationProviderTest extends TestCase
{
    private GiftCardChannelConfigurationRepositoryInterface $giftCardChannelConfigurationRepository;

    private GiftCardChannelConfigurationProvider $provider;

    protected function setUp(): void
    {
        $this->giftCardChannelConfigurationRepository = $this->createMock(GiftCardChannelConfigurationRepositoryInterface::class);

        $this->provider = new GiftCardChannelConfigurationProvider($this->giftCardChannelConfigurationRepository);
    }

    public function testItThrowsAnExceptionIfTheGiftCardHasMultipleChannelConfigurationsForChannel(): void
    {
        $channel = new Channel();
        $channel->setCode('web');

        $this->giftCardChannelConfigurationRepository
            ->expects(self::once())
            ->method('findOneEnabledByChannel')
            ->with($channel)
            ->willThrowException(new NonUniqueResultException());

        $this->expectException(ChannelConfigurationNotFoundException::class);
        $this->expectExceptionMessage('Multiple enabled channel configurations found for channel "web"');

        $this->provider->provideConfigurationForChannel($channel);
    }

    public function testItThrowsAnExceptionIfThereAreNoChannelConfigurationsFoundForChannel(): void
    {
        $channel = new Channel();
        $channel->setCode('web');

        $this->giftCardChannelConfigurationRepository
            ->expects(self::once())
            ->method('findOneEnabledByChannel')
            ->with($channel)
            ->willReturn(null);

        $this->expectException(ChannelConfigurationNotFoundException::class);
        $this->expectExceptionMessage('No configuration found for channel "web"');

        $this->provider->provideConfigurationForChannel($channel);
    }

    /**
     * @throws ChannelConfigurationNotFoundException
     */
    public function testItReturnsTheEnabledChannelConfiguration(): void
    {
        $channel = new Channel();
        $channel->setCode('web');

        $configuration = new GiftCardChannelConfiguration();

        $this->giftCardChannelConfigurationRepository
            ->expects(self::once())
            ->method('findOneEnabledByChannel')
            ->with($channel)
            ->willReturn($configuration);

        self::assertSame($configuration, $this->provider->provideConfigurationForChannel($channel));
    }
}
