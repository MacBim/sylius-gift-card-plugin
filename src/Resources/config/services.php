<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Macbim\SyliusGiftCardsPlugin\Api\Assigner\GiftCardOrPromotionCouponAssigner;

return static function (ContainerConfigurator $container): void {
    $container
        ->parameters()
        ->set('macbim_sylius_gift_cards.grid.date_format', 'Y-m-d');

    $container->import('services/*.php');

    $services = $container->services();

    $services
        ->set('macbim_sylius_gift_cards.gift_card_or_promotion_coupon_assigner', GiftCardOrPromotionCouponAssigner::class)
        ->arg('$giftCardRepository', service('macbim_sylius_gift_cards.repository.gift_card'))
        ->arg('$orderProcessor', service('sylius.order_processing.order_processor'))
        ->arg('$promotionCodeAssigner', service('macbim_sylius_gift_cards.gift_card_or_promotion_coupon_assigner.inner'));
};
