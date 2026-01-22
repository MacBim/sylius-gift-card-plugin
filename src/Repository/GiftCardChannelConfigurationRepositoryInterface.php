<?php

namespace Macbim\SyliusGiftCardsPlugin\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCardChannelConfiguration;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCardChannelConfigurationInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @extends RepositoryInterface<GiftCardChannelConfiguration>
 */
interface GiftCardChannelConfigurationRepositoryInterface extends RepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder;

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByChannel(ChannelInterface $channel): ?GiftCardChannelConfigurationInterface;
}
