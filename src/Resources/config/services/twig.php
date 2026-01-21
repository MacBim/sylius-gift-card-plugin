<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Macbim\SyliusGiftCardsPlugin\Twig\Extension\GiftCardExtension;
use Macbim\SyliusGiftCardsPlugin\Twig\Runtime\GiftCardRuntime;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set('macbim_gift_card_plugin.twig.runtime.gift_card', GiftCardRuntime::class)
        ->arg('$giftCardRepository', service('macbim_sylius_gift_cards.repository.gift_card'))
        ->tag('twig.runtime');

    $services
        ->set('macbim_gift_card_plugin.twig.extension.gift_card', GiftCardExtension::class)
        ->tag('twig.extension');
};
