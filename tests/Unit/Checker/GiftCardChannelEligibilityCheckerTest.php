<?php

declare(strict_types=1);

namespace Unit\Checker;

use Macbim\SyliusGiftCardsPlugin\Checker\GiftCard\GiftCardChannelEligibilityChecker;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCard;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Channel;
use Tests\Macbim\SyliusGiftCardsPlugin\Model\Order;

#[CoversClass(GiftCardChannelEligibilityChecker::class)]
class GiftCardChannelEligibilityCheckerTest extends TestCase
{
    private GiftCardChannelEligibilityChecker $checker;

    protected function setUp(): void
    {
        $this->checker = new GiftCardChannelEligibilityChecker();
    }

    #[DataProvider('provideData')]
    public function testItCorrectlyChecks(string $giftCardChannelCode, string $orderChannelCode, bool $expected): void
    {
        $giftCardChannel = new Channel();
        $giftCardChannel->setCode($giftCardChannelCode);
        $giftCard = new GiftCard();
        $giftCard->setChannel($giftCardChannel);

        $orderChannel = new Channel();
        $orderChannel->setCode($orderChannelCode);
        $order = new Order();
        $order->setChannel($orderChannel);

        self::assertEquals($expected, $this->checker->isEligible($giftCard, $order));
    }

    public static function provideData(): iterable
    {
        yield 'Same channel codes' => ['web', 'web', true];
        yield 'Different channel codes' => ['mobile', 'web', false];
        yield 'Empty channel codes' => ['', 'web', false];
    }
}
