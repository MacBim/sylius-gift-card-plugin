<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Macbim\SyliusGiftCardsPlugin\Factory\GiftCard\GiftCardAdjustmentFactory;
use Macbim\SyliusGiftCardsPlugin\Factory\GiftCard\GiftCardAdjustmentFactoryInterface;
use Macbim\SyliusGiftCardsPlugin\Factory\GiftCard\GiftCardFactory;
use Macbim\SyliusGiftCardsPlugin\Factory\GiftCard\GiftCardFactoryInterface;
use Macbim\SyliusGiftCardsPlugin\Factory\Pdf\DefaultResponseFactory;
use Macbim\SyliusGiftCardsPlugin\Factory\Pdf\PdfResponseFactoryInterface;

return static function (ContainerConfigurator $container): void {
    $container
        ->parameters()
        ->set('macbim_sylius_gift_cards.default_expiration_delay', '1 year')
        ->set('macbim_sylius_gift_cards.default_currency_code', 'EUR');

    $services = $container->services();

    $services
        ->set('macbim_sylius_gift_cards.custom_factory.gift_card', GiftCardFactory::class)
        ->decorate('macbim_sylius_gift_cards.factory.gift_card')
        ->arg('$decoratedFactory', service('macbim_sylius_gift_cards.custom_factory.gift_card.inner'))
        ->arg('$defaultCurrencyCode', param('macbim_sylius_gift_cards.default_currency_code'))
        ->arg('$defaultExpirationDelay', param('macbim_sylius_gift_cards.default_expiration_delay'))
        ->arg('$giftCardCodeGenerator', service('macbim_sylius_gift_cards.generator.code_generator'))
        ->arg('$giftCardChannelConfigurationProvider', service('macbim_sylius_gift_cards.provider.channel_configuration'))
        ->arg('$clock', service('clock'));

    $services->alias(GiftCardFactoryInterface::class, 'macbim_sylius_gift_cards.factory.gift_card');

    $services
        ->set('macbim_sylius_gift_cards.factory.gift_card_adjustment', GiftCardAdjustmentFactory::class)
        ->arg('$adjustmentFactory', service('sylius.factory.adjustment'));

    $services->alias(GiftCardAdjustmentFactoryInterface::class, 'macbim_sylius_gift_cards.factory.gift_card_adjustment');

    $services
        ->set('macbim_sylius_gift_cards.factory.pdf_response', DefaultResponseFactory::class)
        ->arg('$giftCardPdfContentRenderer', service('macbim_sylius_gift_cards.renderer.pdf_content_renderer'));

    $services->alias(PdfResponseFactoryInterface::class, 'macbim_sylius_gift_cards.factory.pdf_response');
};
