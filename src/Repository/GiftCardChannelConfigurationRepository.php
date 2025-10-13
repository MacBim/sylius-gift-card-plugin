<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class GiftCardChannelConfigurationRepository extends EntityRepository implements GiftCardChannelConfigurationRepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('gcc');
    }
}
