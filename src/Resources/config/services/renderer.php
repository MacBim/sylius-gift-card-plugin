<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Macbim\SyliusGiftCardsPlugin\Renderer\GiftCardPdfContentContentRenderer;
use Macbim\SyliusGiftCardsPlugin\Renderer\GiftCardPdfContentRendererInterface;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set('macbim_sylius_gift_cards.renderer.pdf_content_renderer', GiftCardPdfContentContentRenderer::class)
        ->arg('$twig', service('twig'));

    $services->alias(GiftCardPdfContentRendererInterface::class, 'macbim_sylius_gift_cards.renderer.pdf_renderer');
};
