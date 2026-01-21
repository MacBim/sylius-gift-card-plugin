<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Macbim\SyliusGiftCardsPlugin\Workflow\CreateGiftCard;
use Macbim\SyliusGiftCardsPlugin\Workflow\DecreaseGiftCardAmountModifierWorkflow;
use Macbim\SyliusGiftCardsPlugin\Workflow\DisableGiftCardsOnRefund;
use Macbim\SyliusGiftCardsPlugin\Workflow\IncreaseGiftCardAmountModifierWorkflow;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services
        ->defaults()
        ->public();

    $services
        ->set('macbim_gift_card_plugin.workflow.decrement_amount', DecreaseGiftCardAmountModifierWorkflow::class)
        ->arg('$giftCardAmountModifier', service('macbim_sylius_gift_cards.modifier.amount_modifier'));

    $services
        ->set('macbim_gift_card_plugin.workflow.increase_amount', IncreaseGiftCardAmountModifierWorkflow::class)
        ->arg('$giftCardAmountModifier', service('macbim_sylius_gift_cards.modifier.amount_modifier'));

    $services
        ->set('macbim_gift_card_plugin.workflow.create_gift_cards', CreateGiftCard::class)
        ->arg('$giftCardFactory', service('macbim_sylius_gift_cards.custom_factory.gift_card'))
        ->arg('$entityManager', service('doctrine.orm.entity_manager'));

    $services
        ->set('macbim_gift_card_plugin.workflow.disable_gift_cards_on_refund', DisableGiftCardsOnRefund::class)
        ->arg('$giftCardRepository', service('macbim_sylius_gift_cards.repository.gift_card'));
};
