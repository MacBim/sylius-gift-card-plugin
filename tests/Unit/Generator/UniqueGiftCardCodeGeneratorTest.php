<?php

declare(strict_types=1);

namespace Unit\Generator;

use Macbim\SyliusGiftCardsPlugin\Generator\UniqueGiftCardCodeGenerator;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCard;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Random\RandomException;
use Sylius\Component\Resource\Repository\RepositoryInterface;

#[CoversClass(UniqueGiftCardCodeGenerator::class)]
final class UniqueGiftCardCodeGeneratorTest extends TestCase
{
    private RepositoryInterface $giftCardRepository;

    private UniqueGiftCardCodeGenerator $generator;

    protected function setUp(): void
    {
        $this->giftCardRepository = $this->createMock(RepositoryInterface::class);

        $this->generator = new UniqueGiftCardCodeGenerator($this->giftCardRepository, 10);
    }

    /**
     * @throws RandomException
     */
    public function testItReturnsAStringOfCorrectLength(): void
    {
        $this->giftCardRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->willReturn(null);

        $code = $this->generator->generate();

        self::assertSame(10, mb_strlen($code));
    }

    /**
     * @throws RandomException
     */
    public function testItLoopsWhileTheGeneratedCodeExists(): void
    {
        $giftCard = new GiftCard();

        $this->giftCardRepository
            ->expects(self::exactly(2))
            ->method('findOneBy')
            ->willReturnOnConsecutiveCalls($giftCard, null);

        $code = $this->generator->generate();

        self::assertSame(10, mb_strlen($code));
    }
}
