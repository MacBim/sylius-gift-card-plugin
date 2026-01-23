<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Action;

use Macbim\SyliusGiftCardsPlugin\Factory\Pdf\PdfResponseFactoryInterface;
use Macbim\SyliusGiftCardsPlugin\Repository\GiftCardRepositoryInterface;
use Macbim\SyliusGiftCardsPlugin\Voter\GiftCard\DownloadPdfVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class DownloadGiftCardPdfAction
{
    public function __construct(
        private readonly GiftCardRepositoryInterface $giftCardRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly PdfResponseFactoryInterface $pdfResponseFactory,
    ) {}

    public function __invoke(Request $request, string $code): Response
    {
        $giftCard = $this->giftCardRepository->findOneBy(['code' => $code]);
        if (null === $giftCard) {
            throw new NotFoundHttpException(sprintf('Gift card with code "%s" does not exist.', $code));
        }

        if (!$this->authorizationChecker->isGranted(DownloadPdfVoter::DOWNLOAD_PDF, $giftCard)) {
            throw new UnauthorizedHttpException('You do not have permission to download this gift card.');
        }

        return $this->pdfResponseFactory->createResponse($giftCard);
    }
}
