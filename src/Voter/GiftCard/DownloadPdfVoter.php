<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Voter\GiftCard;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class DownloadPdfVoter extends Voter
{
    public const DOWNLOAD_PDF = 'download_pdf';

    public function __construct(
        private readonly AccessDecisionManagerInterface $accessDecisionManager
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof GiftCardInterface && self::DOWNLOAD_PDF === $attribute;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (!$subject instanceof GiftCardInterface) {
            throw new \InvalidArgumentException('The subject must be an instance of GiftCardInterface.');
        }

        if ($this->accessDecisionManager->decide($token, [AdminUserInterface::DEFAULT_ADMIN_ROLE])) {
            return true;
        }

        $user = $token->getUser();
        if ($user instanceof ShopUserInterface) {
            $loggedInCustomer = $user->getCustomer();
            if (null !== $loggedInCustomer) {
                return $loggedInCustomer->getId() === $subject->getCustomer()?->getId();
            }
        }

        return false;
    }
}
