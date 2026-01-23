<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Macbim\SyliusGiftCardsPlugin\Voter\GiftCard\DownloadPdfVoter;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set('macbim_sylius_gift_cards.voter.download_gift_card_pdf', DownloadPdfVoter::class)
        ->arg('$accessDecisionManager', service('security.access.decision_manager'))
        ->tag('security.voter');
};
