<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Macbim\SyliusGiftCardsPlugin\Api\Validator\GiftCardOrPromotionCouponEligibilityValidator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set('macbim_sylius_gift_cards.validator.gift_card_eligibility', GiftCardOrPromotionCouponEligibilityValidator::class)
        ->arg('$orderRepository', service('sylius.repository.order'))
        ->arg('$giftCardRepository', service('macbim_sylius_gift_cards.repository.gift_card'))
        ->arg('$giftCardEligibilityChecker', service('macbim_sylius_gift_cards.checker.gift_card_eligibility_checker'))
        ->arg('$promotionCouponValidator', service('macbim_sylius_gift_cards.validator.gift_card_eligibility.inner'));
};
