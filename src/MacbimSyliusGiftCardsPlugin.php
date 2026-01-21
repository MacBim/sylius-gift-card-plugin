<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;

final class MacbimSyliusGiftCardsPlugin extends AbstractResourceBundle
{
    use SyliusPluginTrait;

    protected string $mappingFormat = self::MAPPING_XML;

    /**
     * @return string[]
     */
    public function getSupportedDrivers(): array
    {
        return [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }
}
