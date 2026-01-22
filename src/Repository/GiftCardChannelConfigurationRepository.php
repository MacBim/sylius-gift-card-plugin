<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCardChannelConfigurationInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\ChannelInterface;

class GiftCardChannelConfigurationRepository extends EntityRepository implements GiftCardChannelConfigurationRepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('gcc');
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByChannel(ChannelInterface $channel): ?GiftCardChannelConfigurationInterface
    {
        return $this->createQueryBuilder('gcc')
            ->andWhere('gcc.channel = :channel')
            ->andWhere('gcc.enabled = true')
            ->setParameter('channel', $channel)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
