<?php

declare(strict_types=1);

namespace Unit\Checker;

use Macbim\SyliusGiftCardsPlugin\Checker\GiftCard\GiftCardEnabledEligibilityChecker;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCard;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Macbim\SyliusGiftCardsPlugin\Model\Order;

#[CoversClass(GiftCardEnabledEligibilityChecker::class)]
class GiftCardEnabledEligibilityCheckerTest extends TestCase
{
    private GiftCardEnabledEligibilityChecker $checker;

    protected function setUp(): void
    {
        $this->checker = new GiftCardEnabledEligibilityChecker();
    }

    #[DataProvider('provideData')]
    public function testItCorrectlyChecks(bool $isEnabled, bool $expected): void
    {
        $giftCard = new GiftCard();
        $giftCard->setEnabled($isEnabled);

        $order = new Order();

        self::assertEquals($expected, $this->checker->isEligible($giftCard, $order));
    }

    public static function provideData(): iterable
    {
        yield 'Gift card is enabled' => [true, true];
        yield 'Gift card is disabled' => [false, false];
    }
}
