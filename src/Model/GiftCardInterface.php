<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Model;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderItemUnitInterface;
use Sylius\Resource\Model\CodeAwareInterface;
use Sylius\Resource\Model\ResourceInterface;

interface GiftCardInterface extends ResourceInterface, CodeAwareInterface
{
    public const ADJUSTMENT_ORIGIN = 'gift_card';

    public function isEnabled(): bool;

    public function setEnabled(bool $enabled): self;

    public function getInitialAmount(): ?int;

    public function setInitialAmount(?int $initialAmount): self;

    public function getAmount(): int;

    public function setAmount(int $amount): self;

    public function getCurrencyCode(): string;

    public function setCurrencyCode(string $currencyCode): self;

    public function getChannel(): ChannelInterface;

    public function setChannel(ChannelInterface $channel): self;

    public function getCustomer(): ?CustomerInterface;

    public function setCustomer(?CustomerInterface $customer): self;

    public function getExpiresAt(): ?\DateTimeImmutable;

    public function setExpiresAt(?\DateTimeImmutable $expiresAt): self;

    /**
     * @return Collection<int, OrderInterface>
     */
    public function getAppliedOrders(): Collection;

    public function addAppliedOrder(OrderInterface $order): self;

    public function removeAppliedOrder(OrderInterface $order): self;

    public function getOrderItemUnit(): ?OrderItemUnitInterface;

    public function setOrderItemUnit(?OrderItemUnitInterface $orderItemUnit): self;
}
