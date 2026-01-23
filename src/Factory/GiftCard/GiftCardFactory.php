<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Factory\GiftCard;

use Macbim\SyliusGiftCardsPlugin\Exception\ChannelConfigurationNotFoundException;
use Macbim\SyliusGiftCardsPlugin\Generator\GiftCardCodeGeneratorInterface;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Provider\GiftCardChannelConfigurationProviderInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderItemUnitInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Symfony\Component\Clock\ClockInterface;

final class GiftCardFactory implements GiftCardFactoryInterface
{
    /**
     * @param FactoryInterface<GiftCardInterface> $decoratedFactory
     */
    public function __construct(
        private readonly FactoryInterface $decoratedFactory,
        private readonly string $defaultCurrencyCode,
        private readonly string $defaultExpirationDelay,
        private readonly GiftCardCodeGeneratorInterface $giftCardCodeGenerator,
        private readonly GiftCardChannelConfigurationProviderInterface $giftCardChannelConfigurationProvider,
        private readonly ClockInterface $clock,
    ) {}

    public function createNew(): GiftCardInterface
    {
        $giftCard = $this->decoratedFactory->createNew();
        $giftCard->setCode($this->giftCardCodeGenerator->generate());
        $giftCard->setCurrencyCode($this->defaultCurrencyCode);

        return $giftCard;
    }

    /**
     * @throws \Exception
     */
    public function createNewForOrderItemUnit(OrderItemUnitInterface $orderItemUnit, ChannelInterface $channel, string $currencyCode): GiftCardInterface
    {
        try {
            $channelConfiguration = $this->giftCardChannelConfigurationProvider->provideConfigurationForChannel($channel);

            $expirationDelay = $channelConfiguration->getExpirationDelay();
        } catch (ChannelConfigurationNotFoundException) {
            $expirationDelay = $this->defaultExpirationDelay;
        }

        $amount = $orderItemUnit->getTotal();

        $giftCard = $this->createNew();
        $giftCard
            ->setOrderItemUnit($orderItemUnit)
            ->setEnabled(true)
            ->setCurrencyCode($currencyCode)
            ->setChannel($channel)
            ->setInitialAmount($amount)
            ->setAmount($amount)
            ->setExpiresAt($this->clock->now()->modify(sprintf('+%s', $expirationDelay)));

        return $giftCard;
    }

    /**
     * @throws \Exception
     */
    public function createNewEnabledForOrderItemUnit(OrderItemUnitInterface $orderItemUnit, ChannelInterface $channel, string $currencyCode): GiftCardInterface
    {
        $giftCard = $this->createNewForOrderItemUnit($orderItemUnit, $channel, $currencyCode);
        $giftCard->setEnabled(true);

        return $giftCard;
    }
}
