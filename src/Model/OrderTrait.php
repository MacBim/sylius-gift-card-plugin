<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\OrderItemInterface;

trait OrderTrait
{
    #[ORM\ManyToMany(targetEntity: GiftCardInterface::class, mappedBy: 'appliedOrders')]
    public Collection $appliedGiftCards;

    public function __construct()
    {
        $this->appliedGiftCards = new ArrayCollection();
    }

    /**
     * @return Collection<int, OrderItemInterface>
     */
    public function getGiftCardsOrderItems(): Collection
    {
        return $this->getItems()->filter(function (OrderItemInterface $orderItem) {
            /** @var ProductInterface|null $product */
            $product = $orderItem->getProduct();

            return true === $product?->isGiftCard();
        });
    }

    public function getAppliedGiftCards(): Collection
    {
        return $this->appliedGiftCards;
    }

    public function addAppliedGiftCard(GiftCardInterface $giftCard): self
    {
        if (!$this->appliedGiftCards->contains($giftCard)) {
            $this->appliedGiftCards->add($giftCard);

            $giftCard->addAppliedOrder($this);
        }

        return $this;
    }

    public function removeAppliedGiftCard(GiftCardInterface $giftCard): self
    {
        if ($this->appliedGiftCards->contains($giftCard)) {
            $this->appliedGiftCards->removeElement($giftCard);

            $giftCard->removeAppliedOrder($this);
        }

        return $this;
    }
}
