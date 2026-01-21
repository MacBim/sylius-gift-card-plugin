<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\EventListener\Menu\Admin;

use Sylius\Bundle\AdminBundle\Event\ProductMenuBuilderEvent;

class ProductFormMenuBuilder
{
    public function __invoke(ProductMenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $menu
            ->addChild('macbim-sylius-gift-cards-gift-card')
            ->setAttribute('template', '@MacbimSyliusGiftCardsPlugin/Admin/Product/Tab/_giftCard.html.twig')
            ->setLabel('macbim_sylius_gift_cards.ui.gift_card');
    }
}
