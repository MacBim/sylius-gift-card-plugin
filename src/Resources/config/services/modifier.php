<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Macbim\SyliusGiftCardsPlugin\Modifier\GiftCard\GiftCardAmountModifier;
use Macbim\SyliusGiftCardsPlugin\Modifier\GiftCard\GiftCardAmountModifierInterface;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set('macbim_sylius_gift_cards.modifier.amount_modifier', GiftCardAmountModifier::class);

    $services->alias(GiftCardAmountModifierInterface::class, 'macbim_sylius_gift_cards.modifier.amount_modifier');
};
