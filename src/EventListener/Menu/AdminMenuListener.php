<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\EventListener\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class AdminMenuListener
{
    public function __invoke(MenuBuilderEvent $event): void
    {
        $marketingNode = $event->getMenu()->getChild('marketing');
        if ($marketingNode === null) {
            return;
        }

        $marketingNode
            ->addChild('macbim_sylius_plugin_gift_cards_gift_card', ['route' => 'macbim_sylius_gift_cards_admin_gift_card_index'])
            ->setLabel('macbim_sylius_gift_cards.ui.gift_cards.header')
            ->setLabelAttribute('icon', 'credit card outline');

        $marketingNode
            ->addChild('macbim_sylius_plugin_gift_cards_gift_card_channel_configuration', ['route' => 'macbim_sylius_gift_cards_admin_gift_card_channel_configuration_index'])
            ->setLabel('macbim_sylius_gift_cards.ui.channel_configuration.header')
            ->setLabelAttribute('icon', 'cogs');
    }
}
