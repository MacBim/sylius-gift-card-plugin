<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Renderer;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class GiftCardPdfContentContentRenderer implements GiftCardPdfContentRendererInterface
{
    public function __construct(
        private readonly Environment $twig,
    ) {}

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function renderContent(GiftCardInterface $giftCard): string
    {
        return $this->twig->render('@MacbimSyliusGiftCardsPlugin/Shop/GiftCard/pdf.html.twig', [
            'giftCard' => $giftCard,
        ]);
    }
}
