<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Macbim\SyliusGiftCardsPlugin\Form\Extension\Product\ProductTypeExtension;
use Macbim\SyliusGiftCardsPlugin\Form\Type\GiftCardChannelConfigurationType;
use Macbim\SyliusGiftCardsPlugin\Form\Type\GiftCardType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductType;

return static function (ContainerConfigurator $container): void {
    $parameters = $container->parameters();

    $parameters
        ->set('macbim_sylius_gift_cards.form.type.gift_card.validation_groups', ['sylius'])
        ->set('macbim_sylius_gift_cards.form.type.gift_card_channel_configuration.validation_groups', ['sylius']);

    $services = $container->services();

    $services
        ->set('macbim_sylius_gift_cards.form.type.gift_card', GiftCardType::class)
        ->arg('$dataClass', param('macbim_sylius_gift_cards.model.gift_card.class'))
        ->arg('$validationGroups', param('macbim_sylius_gift_cards.form.type.gift_card.validation_groups'))
        ->arg('$currencyRepository', service('sylius.repository.currency'))
        ->tag('form.type');

    $services
        ->set('macbim_sylius_gift_cards.form.type.gift_card_channel_configuration', GiftCardChannelConfigurationType::class)
        ->arg('$dataClass', param('macbim_sylius_gift_cards.model.gift_card_channel_configuration.class'))
        ->arg('$validationGroups', param('macbim_sylius_gift_cards.form.type.gift_card_channel_configuration.validation_groups'))
        ->arg('$channelContext', service('sylius.context.channel'))
        ->tag('form.type');

    $services
        ->set('macbim_sylius_gift_cards.form.extension.product', ProductTypeExtension::class)
        ->tag('form.type_extension', ['extended_type' => ProductType::class, 'priority' => 150]);
};
