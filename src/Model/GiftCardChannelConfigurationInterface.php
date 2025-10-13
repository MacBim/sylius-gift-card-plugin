<?php

namespace Macbim\SyliusGiftCardsPlugin\Model;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Resource\Model\CodeAwareInterface;

interface GiftCardChannelConfigurationInterface extends ResourceInterface, CodeAwareInterface
{
    public function getCode(): ?string;

    public function getChannel(): ChannelInterface;

    public function setChannel(ChannelInterface $channel): self;

    public function getExpirationDelay(): string;

    public function setExpirationDelay(string $expirationDelay): self;
}
