<?php

namespace Macbim\SyliusGiftCardsPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCardChannelConfiguration;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @extends RepositoryInterface<GiftCardChannelConfiguration>
 */
interface GiftCardChannelConfigurationRepositoryInterface extends RepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder;
}
