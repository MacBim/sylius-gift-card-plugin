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
            $code = $this->doGenerate();
        } while (mb_strlen($code) !== $this->codeLength && $this->exists($code));

        return $code;
    }

    /**
     * @throws RandomException
     */
    private function doGenerate(): string
    {
        $code = bin2hex(random_bytes($this->codeLength));
        /** @var string $code */
        $code = preg_replace('/[01]/', '', $code); // remove hard to read characters

        return mb_strtoupper(mb_substr($code, 0, $this->codeLength));
    }

    private function exists(string $code): bool
    {
        return null !== $this->giftCardRepository->findOneBy(['code' => $code]);
    }
}
