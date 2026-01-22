<?php

declare(strict_types=1);

namespace Unit\Checker;

use Macbim\SyliusGiftCardsPlugin\Checker\GiftCard\GiftCardIsNotExpiredEligibilityChecker;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCard;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\ClockInterface;
use Tests\Macbim\SyliusGiftCardsPlugin\Model\Order;

#[CoversClass(GiftCardIsNotExpiredEligibilityChecker::class)]
class GiftCardIsNotExpiredEligibilityCheckerTest extends TestCase
{
    private ClockInterface $clock;

    private GiftCardIsNotExpiredEligibilityChecker $checker;

    protected function setUp(): void
    {
        $this->clock = $this->createMock(ClockInterface::class);

        $this->checker = new GiftCardIsNotExpiredEligibilityChecker($this->clock);
    }

    #[DataProvider('provideData')]
    public function testItCorrectlyChecks(?\DateTimeInterface $expiresAt, \DateTimeInterface $now, bool $expected): void
    {
        $giftCard = new GiftCard();
        $giftCard->setExpiresAt($expiresAt);

        $this->clock
            ->expects(null === $expiresAt ? self::never() : self::once())
            ->method('now')
            ->willReturn($now);

        $order = new Order();

        self::assertEquals($expected, $this->checker->isEligible($giftCard, $order));
    }

    public static function provideData(): iterable
    {
        yield 'Gift card is not expired, because no expiration date' => [null, new \DateTimeImmutable(), true];
        yield 'Gift card is not expired' => [new \DateTimeImmutable('+1 day'), new \DateTimeImmutable(), true];
        yield 'Gift card is expired' => [new \DateTimeImmutable('-1 day'), new \DateTimeImmutable(), false];
    }
}
