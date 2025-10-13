<?php

namespace Macbim\SyliusGiftCardsPlugin\Repository;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Doctrine\ORM\QueryBuilder;
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

    public function findForCustomer(CustomerInterface $customer): iterable;

    public function findOneEnabledByCode(string $code): ?GiftCardInterface;
}
