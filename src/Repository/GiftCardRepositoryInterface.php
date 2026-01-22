<?php

namespace Macbim\SyliusGiftCardsPlugin\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Model\OrderInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @extends RepositoryInterface<GiftCardInterface>
 *
 * @template-extends RepositoryInterface<GiftCardInterface>
 */
interface GiftCardRepositoryInterface extends RepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder;

    /**
     * @return iterable<GiftCardInterface>
     */
    public function findForCustomer(CustomerInterface $customer): iterable;

    /**
     * @throws NonUniqueResultException
     */
    public function findOneEnabledByCode(string $code): ?GiftCardInterface;

    /**
     * @return iterable<GiftCardInterface>
     */
    public function findCreatedByOrder(OrderInterface $order): iterable;
}
