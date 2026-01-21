<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Factory;

use Macbim\SyliusGiftCardsPlugin\Exception\ChannelConfigurationNotFoundException;
use Macbim\SyliusGiftCardsPlugin\Generator\GiftCardCodeGeneratorInterface;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Provider\GiftCardChannelConfigurationProviderInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderItemUnitInterface;
use Sylius\Resource\Factory\FactoryInterface;

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
    ) {
    }

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

        $giftCard = $this->createNew();
        $giftCard
            ->setOrderItemUnit($orderItemUnit)
            ->setEnabled(true)
            ->setCurrencyCode($currencyCode)
            ->setChannel($channel)
            ->setInitialAmount($orderItemUnit->getTotal())
            ->setAmount($orderItemUnit->getTotal())
            ->setExpiresAt(new \DateTimeImmutable(sprintf('+ %s', $expirationDelay)));

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
