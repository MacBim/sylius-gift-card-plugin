<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Macbim\SyliusGiftCardsPlugin\Generator\UniqueGiftCardCodeGenerator;

return static function (ContainerConfigurator $container): void {
    $container
        ->parameters()
        ->set('macbim_sylius_gift_cards.code_length', 10);

    $services = $container->services();

    $services
        ->set('macbim_sylius_gift_cards.generator.code_generator', UniqueGiftCardCodeGenerator::class)
        ->arg('$giftCardRepository', service('macbim_sylius_gift_cards.repository.gift_card'))
        ->arg('$codeLength', param('macbim_sylius_gift_cards.code_length'));
};
