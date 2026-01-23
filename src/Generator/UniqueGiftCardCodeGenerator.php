<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Generator;

use Random\RandomException;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class UniqueGiftCardCodeGenerator implements GiftCardCodeGeneratorInterface
{
    public function __construct(
        private readonly RepositoryInterface $giftCardRepository,
        private readonly int $codeLength,
    ) {}

    /**
     * @throws RandomException
     */
    public function generate(): string
    {
        do {
            $hash = bin2hex(random_bytes(20));

            $code = strtoupper(substr($hash, 0, $this->codeLength));
        } while ($this->exists($code));

        return $code;
    }

    private function exists(string $code): bool
    {
        return null !== $this->giftCardRepository->findOneBy(['code' => $code]);
    }
}
