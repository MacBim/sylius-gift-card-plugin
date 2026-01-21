<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Twig\Extension;

use Macbim\SyliusGiftCardsPlugin\Twig\Runtime\GiftCardRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GiftCardExtension extends AbstractExtension
{
    /**
     * @return array<TwigFunction>
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('macbim_sylius_gift_cards_find_gift_card_for_order', [GiftCardRuntime::class, 'getGiftCardsCreatedByOrder']),
        ];
    }
}
