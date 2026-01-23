<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Macbim\SyliusGiftCardsPlugin\Provider\GiftCardChannelConfigurationProvider;
use Macbim\SyliusGiftCardsPlugin\Provider\GiftCardChannelConfigurationProviderInterface;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set('macbim_sylius_gift_cards.provider.channel_configuration', GiftCardChannelConfigurationProvider::class)
        ->arg('$configurationRepository', service('macbim_sylius_gift_cards.repository.gift_card_channel_configuration'));

    $services->alias(GiftCardChannelConfigurationProviderInterface::class, 'macbim_sylius_gift_cards.provider.channel_configuration');
};
