<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Generator;

interface GiftCardCodeGeneratorInterface
{
    public function generate(): string;
}
