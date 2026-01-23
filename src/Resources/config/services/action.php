<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Macbim\SyliusGiftCardsPlugin\Action\DownloadGiftCardPdfAction;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set('macbim_sylius_gift_cards.action.gift_cards.download_pdf', DownloadGiftCardPdfAction::class)
        ->arg('$pdfResponseFactory', service('macbim_sylius_gift_cards.factory.pdf_response'))
        ->arg('$authorizationChecker', service('security.authorization_checker'))
        ->arg('$giftCardRepository', service('macbim_sylius_gift_cards.repository.gift_card'))
        ->tag('controller.service_arguments');
};
