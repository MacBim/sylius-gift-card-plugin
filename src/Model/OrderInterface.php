<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Model;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

interface OrderInterface extends BaseOrderInterface
{
    /**
     * @return Collection<int, OrderItemInterface>
     */
    public function getGiftCardsOrderItems(): Collection;

    /**
     * @return Collection<int, GiftCardInterface>
     */
    public function getAppliedGiftCards(): Collection;

    public function addAppliedGiftCard(GiftCardInterface $giftCard): self;

    public function removeAppliedGiftCard(GiftCardInterface $giftCard): self;

}
