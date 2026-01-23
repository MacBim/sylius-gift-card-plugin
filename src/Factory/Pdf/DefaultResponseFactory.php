<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Factory\Pdf;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Renderer\GiftCardPdfContentRendererInterface;
use Symfony\Component\HttpFoundation\Response;

final class DefaultResponseFactory implements PdfResponseFactoryInterface
{
    public function __construct(
        /** @phpstan-ignore-next-line */
        private readonly GiftCardPdfContentRendererInterface $giftCardPdfContentRenderer,
    ) {}

    public function createResponse(GiftCardInterface $giftCard): Response
    {
        throw new \LogicException('You must override this service to implement your PDF generation logic, and to return a valid PDF response.');
    }
}
