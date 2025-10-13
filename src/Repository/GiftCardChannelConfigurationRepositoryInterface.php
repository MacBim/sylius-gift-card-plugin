<?php

namespace Macbim\SyliusGiftCardsPlugin\Repository;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardChannelConfiguration;
use Doctrine\ORM\QueryBuilder;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @extends RepositoryInterface<GiftCardChannelConfiguration>
 */
interface GiftCardChannelConfigurationRepositoryInterface extends RepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder;
}
