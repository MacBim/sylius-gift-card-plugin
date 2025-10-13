<?php

namespace Macbim\SyliusGiftCardsPlugin\Repository;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\CustomerInterface;

class GiftCardRepository extends EntityRepository implements GiftCardRepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('g');
    }

    /**
     * @return iterable<GiftCardInterface>
     */
    public function findForCustomer(CustomerInterface $customer): iterable
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.customer = :customer')
            ->setParameter('customer', $customer)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneEnabledByCode(string $code): ?GiftCardInterface
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.code = :code')
            ->andWhere('g.enabled = true')
            ->setParameter('code', $code, Types::STRING)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
