<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $container
        ->parameters()
        ->set('macbim_sylius_gift_cards.grid.date_format', 'Y-m-d');

    $container->import('services/*.php');
};
