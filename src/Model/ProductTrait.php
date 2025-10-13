<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Model;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait ProductTrait
{
    #[ORM\Column(name: 'is_gift_card', type: Types::BOOLEAN, options: ['default' => 'false'])]
    public bool $isGiftCard = false;

    public function isGiftCard(): bool
    {
        return $this->isGiftCard;
    }

    public function setIsGiftCard(bool $isGiftCard): self
    {
        $this->isGiftCard = $isGiftCard;

        return $this;
    }
}
