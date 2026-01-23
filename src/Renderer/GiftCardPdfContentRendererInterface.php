<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Renderer;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;

interface GiftCardPdfContentRendererInterface
{
    public function renderContent(GiftCardInterface $giftCard): string;
}
