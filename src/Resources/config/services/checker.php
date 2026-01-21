<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Macbim\SyliusGiftCardsPlugin\Checker\GiftCard\CompositeGiftCardEligibilityChecker;
use Macbim\SyliusGiftCardsPlugin\Checker\GiftCard\GiftCardChannelEligibilityChecker;
use Macbim\SyliusGiftCardsPlugin\Checker\GiftCard\GiftCardEligibilityCheckerInterface;
use Macbim\SyliusGiftCardsPlugin\Checker\GiftCard\GiftCardEnabledEligibilityChecker;
use Macbim\SyliusGiftCardsPlugin\Checker\GiftCard\GiftCardIsNotExpiredEligibilityChecker;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $checkerTag = 'macbim_sylius_gift_cards.checker.gift_card_eligibility_checker';

    $services
        ->set('macbim_sylius_gift_cards.checker.gift_card_eligibility_checker.not_expired', GiftCardIsNotExpiredEligibilityChecker::class)
        ->arg('$clock', service('clock'))
        ->tag($checkerTag);

    $services
        ->set('macbim_sylius_gift_cards.checker.gift_card_eligibility_checker.same_channel', GiftCardChannelEligibilityChecker::class)
        ->tag($checkerTag);

    $services
        ->set('macbim_sylius_gift_cards.checker.gift_card_eligibility_checker.is_enabled', GiftCardEnabledEligibilityChecker::class)
        ->tag($checkerTag);

    $services
        ->set('macbim_sylius_gift_cards.checker.gift_card_eligibility_checker', CompositeGiftCardEligibilityChecker::class)
        ->arg('$checkers', tagged_iterator('macbim_sylius_gift_cards.checker.gift_card_eligibility_checker'));

    $services->alias(GiftCardEligibilityCheckerInterface::class, 'macbim_sylius_gift_cards.checker.gift_card_eligibility_checker');
};
