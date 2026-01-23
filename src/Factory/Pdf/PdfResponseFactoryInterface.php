<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Factory\Pdf;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Symfony\Component\HttpFoundation\Response;

interface PdfResponseFactoryInterface
{
    public function createResponse(GiftCardInterface $giftCard): Response;
}
