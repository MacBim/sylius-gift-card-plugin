<?php

declare(strict_types=1);

namespace Tests\Macbim\SyliusGiftCardsPlugin\Model;

use Doctrine\ORM\Mapping as ORM;
use Macbim\SyliusGiftCardsPlugin\Model\ProductTrait as MacBimGiftCardsPluginProductTrait;
use Sylius\Component\Core\Model\Product as BaseProduct;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_product')]
class Product extends BaseProduct
{
    use MacBimGiftCardsPluginProductTrait;
}
