<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Macbim\SyliusGiftCardsPlugin\Processor\GiftCardOrderProcessor;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    // This processor MUST be prioritized between the OrderAdjustmentsClearer (20) and the OrderPromotionProcessor (10)
    $services
        ->set('macbim_sylius_gift_cards.order_processing.gift_card_order_processor', GiftCardOrderProcessor::class)
        ->arg('$giftCardAdjustmentFactory', service('macbim_sylius_gift_cards.factory.gift_card_adjustment'))
        ->tag('sylius.order_processor', ['priority' => 15]);
};
