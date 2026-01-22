<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Macbim\SyliusGiftCardsPlugin\Factory\GiftCardAdjustmentFactory;
use Macbim\SyliusGiftCardsPlugin\Factory\GiftCardAdjustmentFactoryInterface;
use Macbim\SyliusGiftCardsPlugin\Factory\GiftCardFactory;
use Macbim\SyliusGiftCardsPlugin\Factory\GiftCardFactoryInterface;

return static function (ContainerConfigurator $container): void {
    $container
        ->parameters()
        ->set('macbim_sylius_gift_cards.default_expiration_delay', '1 year')
        ->set('macbim_sylius_gift_cards.default_currency_code', 'EUR');

    $services = $container->services();

    $services
        ->set('macbim_sylius_gift_cards.custom_factory.gift_card', GiftCardFactory::class)
        ->decorate('macbim_sylius_gift_cards.factory.gift_card')
        ->arg('$decoratedFactory', service('macbim_sylius_gift_cards.custom_factory.gift_card.inner'))
        ->arg('$defaultCurrencyCode', param('macbim_sylius_gift_cards.default_currency_code'))
        ->arg('$defaultExpirationDelay', param('macbim_sylius_gift_cards.default_expiration_delay'))
        ->arg('$giftCardCodeGenerator', service('macbim_sylius_gift_cards.generator.code_generator'))
        ->arg('$giftCardChannelConfigurationProvider', service('macbim_sylius_gift_cards.provider.channel_configuration'))
        ->arg('$clock', service('clock'));

    $services->alias(GiftCardFactoryInterface::class, 'macbim_sylius_gift_cards.factory.gift_card');

    $services
        ->set('macbim_sylius_gift_cards.factory.gift_card_adjustment', GiftCardAdjustmentFactory::class)
        ->arg('$adjustmentFactory', service('sylius.factory.adjustment'));

    $services->alias(GiftCardAdjustmentFactoryInterface::class, 'macbim_sylius_gift_cards.factory.gift_card_adjustment');
};
