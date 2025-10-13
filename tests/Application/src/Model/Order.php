<?php

declare(strict_types=1);

namespace Tests\Macbim\SyliusGiftCardsPlugin\Model;

use Macbim\SyliusGiftCardsPlugin\Model\OrderInterface;
use Macbim\SyliusGiftCardsPlugin\Model\OrderTrait;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Order as BaseOrder;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_order')]
class Order extends BaseOrder implements OrderInterface
{
    use OrderTrait {
        __construct as private initializeOrderTrait;
    }

    public function __construct()
    {
        parent::__construct();

        $this->initializeOrderTrait();
    }
}
