<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Core\Model\OrderItemUnitInterface;

#[ORM\MappedSuperclass]
#[ORM\Table(name: 'macbim_sylius_gift_cards_gift_card')]
class GiftCard implements GiftCardInterface
{
    use TimestampableEntity;

    #[ORM\Id()]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER, options: ['unsigned' => true])]
    private ?int $id;

    #[ORM\Column(name: 'code', type: Types::STRING)]
    private ?string $code = null;

    #[ORM\Column(name: 'enabled', type: Types::BOOLEAN, options: ['default' => true])]
    private bool $enabled = false;

    #[ORM\Column(name: 'initial_amount', type: Types::INTEGER, nullable: true, options: ['default' => null])]
    private ?int $initialAmount = null;

    #[ORM\Column(name: 'amount', type: Types::INTEGER, options: ['default' => 0])]
    private int $amount = 0;

    #[ORM\Column(name: 'currency_code', type: Types::STRING, options: ['length' => 3])]
    private string $currencyCode;

    #[ORM\ManyToOne(targetEntity: ChannelInterface::class)]
    #[ORM\JoinColumn(name: 'channel_id', referencedColumnName: 'id', nullable: false)]
    private ChannelInterface $channel;

    #[ORM\ManyToOne(targetEntity: CustomerInterface::class)]
    #[ORM\JoinColumn(name: 'customer_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?CustomerInterface $customer;

    #[ORM\Column(name: 'expires_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $expiresAt = null;

    /**
     * @var Collection<int, OrderInterface>
     */
    #[ORM\ManyToMany(targetEntity: BaseOrderInterface::class, inversedBy: 'appliedGiftCards')]
    #[ORM\JoinTable(name: 'macbim_sylius_gift_cards_order')]
    private Collection $appliedOrders;

    #[ORM\ManyToOne(targetEntity: OrderItemUnitInterface::class)]
    #[ORM\JoinColumn(name: 'order_item_unit_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?OrderItemUnitInterface $orderItemUnit;

    public function __construct()
    {
        $this->appliedOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getInitialAmount(): ?int
    {
        return $this->initialAmount;
    }

    public function setInitialAmount(?int $initialAmount): self
    {
        if (null !== $this->initialAmount) {
            throw new \LogicException('Gift card amount cannot be changed.');
        }

        $this->initialAmount = $initialAmount;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        if (null === $this->getInitialAmount()) {
            $this->setInitialAmount($amount);
        }

        $this->amount = $amount;

        return $this;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): self
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    public function getChannel(): ChannelInterface
    {
        return $this->channel;
    }

    public function setChannel(ChannelInterface $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    public function setCustomer(?CustomerInterface $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeImmutable $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * @return Collection<int, OrderInterface>
     */
    public function getAppliedOrders(): Collection
    {
        return $this->appliedOrders;
    }

    public function addAppliedOrder(OrderInterface $order): self
    {
        if (!$this->appliedOrders->contains($order)) {
            $this->appliedOrders->add($order);
        }

        return $this;
    }

    public function removeAppliedOrder(OrderInterface $order): self
    {
        $this->appliedOrders->removeElement($order);

        return $this;
    }

    public function getOrderItemUnit(): ?OrderItemUnitInterface
    {
        return $this->orderItemUnit;
    }

    public function setOrderItemUnit(?OrderItemUnitInterface $orderItemUnit): self
    {
        $this->orderItemUnit = $orderItemUnit;

        return $this;
    }
}
