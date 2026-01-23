<?php

declare(strict_types=1);

namespace Unit\Factory;

use Macbim\SyliusGiftCardsPlugin\Exception\ChannelConfigurationNotFoundException;
use Macbim\SyliusGiftCardsPlugin\Factory\GiftCard\GiftCardFactory;
use Macbim\SyliusGiftCardsPlugin\Generator\GiftCardCodeGeneratorInterface;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCard;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCardChannelConfiguration;
use Macbim\SyliusGiftCardsPlugin\Provider\GiftCardChannelConfigurationProviderInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\OrderItemUnitInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Clock\MockClock;

#[CoversClass(GiftCardFactory::class)]
class GiftCardFactoryTest extends TestCase
{
    private FactoryInterface $decoratedFactory;

    private GiftCardCodeGeneratorInterface $codeGenerator;

    private GiftCardChannelConfigurationProviderInterface $channelConfigurationProvider;

    private ClockInterface $clock;

    private GiftCardFactory $giftCardFactory;

    protected function setUp(): void
    {
        $this->decoratedFactory = $this->createMock(FactoryInterface::class);

        $this->codeGenerator = $this->createMock(GiftCardCodeGeneratorInterface::class);

        $this->channelConfigurationProvider = $this->createMock(GiftCardChannelConfigurationProviderInterface::class);

        $this->clock = new MockClock('2026-01-01');

        $this->giftCardFactory = new GiftCardFactory(
            $this->decoratedFactory,
            'EUR',
            '500 year',
            $this->codeGenerator,
            $this->channelConfigurationProvider,
            $this->clock,
        );
    }

    public function testCreateNew(): void
    {
        $giftCard = new GiftCard();

        $this->decoratedFactory
            ->expects(self::once())
            ->method('createNew')
            ->willReturn($giftCard);

        $this->codeGenerator
            ->expects(self::once())
            ->method('generate')
            ->willReturn('gift_card_unique_code');

        self::assertSame($giftCard, $this->giftCardFactory->createNew());

        self::assertEquals('EUR', $giftCard->getCurrencyCode());
        self::assertEquals('gift_card_unique_code', $giftCard->getCode());
    }

    /**
     * @throws \DateMalformedStringException
     * @throws \Exception
     */
    public function testCreateNewForOrderItemUnitWithChannelConfiguration(): void
    {
        $channel = new Channel();

        $orderItemUnit = $this->createMock(OrderItemUnitInterface::class);
        $orderItemUnit
            ->expects(self::once())
            ->method('getTotal')
            ->willReturn(10000); // 100 EUR

        $giftCard = new GiftCard();

        $this->decoratedFactory
            ->expects(self::once())
            ->method('createNew')
            ->willReturn($giftCard);

        $this->codeGenerator
            ->expects(self::once())
            ->method('generate')
            ->willReturn('gift_card_unique_code');

        $channelConfiguration = new GiftCardChannelConfiguration();
        $channelConfiguration->setExpirationDelay('2 year');

        $this->channelConfigurationProvider
            ->expects(self::once())
            ->method('provideConfigurationForChannel')
            ->with($channel)
            ->willReturn($channelConfiguration);

        self::assertSame($giftCard, $this->giftCardFactory->createNewForOrderItemUnit($orderItemUnit, $channel, 'USD'));

        self::assertTrue($giftCard->isEnabled());
        self::assertSame($orderItemUnit, $giftCard->getOrderItemUnit());
        self::assertSame($channel, $giftCard->getChannel());
        self::assertEquals('USD', $giftCard->getCurrencyCode());
        self::assertEquals(10000, $giftCard->getInitialAmount());
        self::assertEquals(10000, $giftCard->getAmount());
        self::assertEquals('gift_card_unique_code', $giftCard->getCode());
        self::assertEquals($this->clock->now()->modify(sprintf('+%s', $channelConfiguration->getExpirationDelay())), $giftCard->getExpiresAt());
    }

    public function testCreateNewForOrderItemUnitWithoutChannelConfiguration(): void
    {
        $channel = new Channel();

        $orderItemUnit = $this->createMock(OrderItemUnitInterface::class);
        $orderItemUnit
            ->expects(self::once())
            ->method('getTotal')
            ->willReturn(10000); // 100 EUR

        $giftCard = new GiftCard();

        $this->decoratedFactory
            ->expects(self::once())
            ->method('createNew')
            ->willReturn($giftCard);

        $this->codeGenerator
            ->expects(self::once())
            ->method('generate')
            ->willReturn('gift_card_unique_code');

        $this->channelConfigurationProvider
            ->expects(self::once())
            ->method('provideConfigurationForChannel')
            ->with($channel)
            ->willThrowException(new ChannelConfigurationNotFoundException());

        self::assertSame($giftCard, $this->giftCardFactory->createNewForOrderItemUnit($orderItemUnit, $channel, 'USD'));

        self::assertTrue($giftCard->isEnabled());
        self::assertSame($orderItemUnit, $giftCard->getOrderItemUnit());
        self::assertSame($channel, $giftCard->getChannel());
        self::assertEquals('USD', $giftCard->getCurrencyCode());
        self::assertEquals(10000, $giftCard->getInitialAmount());
        self::assertEquals(10000, $giftCard->getAmount());
        self::assertEquals('gift_card_unique_code', $giftCard->getCode());
        self::assertEquals($this->clock->now()->modify('+500 year'), $giftCard->getExpiresAt());
    }
}
