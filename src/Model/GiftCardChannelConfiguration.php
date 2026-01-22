<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Model;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\ChannelInterface;

#[ORM\MappedSuperclass]
#[ORM\Table(name: 'macbim_sylius_gift_cards_gift_card_channel_configuration')]
class GiftCardChannelConfiguration implements GiftCardChannelConfigurationInterface
{
    #[ORM\Id()]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER, options: ['unsigned' => true])]
    public ?int $id;

    #[ORM\Column(name: 'code', type: Types::STRING)]
    private ?string $code = null;

    #[ORM\Column(name: 'enabled', type: Types::BOOLEAN, options: ['default' => false])]
    public bool $enabled = false;

    #[ORM\ManyToOne(targetEntity: ChannelInterface::class)]
    #[ORM\JoinColumn(name: 'channel_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ChannelInterface $channel;

    #[ORM\Column(name: 'expiration_delay', type: Types::STRING)]
    private string $expirationDelay;

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

    public function getChannel(): ChannelInterface
    {
        return $this->channel;
    }

    public function setChannel(ChannelInterface $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function getExpirationDelay(): string
    {
        return $this->expirationDelay;
    }

    public function setExpirationDelay(string $expirationDelay): self
    {
        $this->expirationDelay = $expirationDelay;

        return $this;
    }
}
