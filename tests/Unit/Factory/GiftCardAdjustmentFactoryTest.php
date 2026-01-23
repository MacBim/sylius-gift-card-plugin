<?php

declare(strict_types=1);

namespace Unit\Factory;

use Macbim\SyliusGiftCardsPlugin\Factory\GiftCard\GiftCardAdjustmentFactory;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCard;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Model\OrderInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Adjustment;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;

#[CoversClass(GiftCardAdjustmentFactory::class)]
class GiftCardAdjustmentFactoryTest extends TestCase
{
    private AdjustmentFactoryInterface $adjustmentFactory;

    private GiftCardAdjustmentFactory $giftCardAdjustmentFactory;

    protected function setUp(): void
    {
        $this->adjustmentFactory = $this->createMock(AdjustmentFactoryInterface::class);

        $this->giftCardAdjustmentFactory = new GiftCardAdjustmentFactory($this->adjustmentFactory);
    }

    #[DataProvider('provideData')]
    public function testItCreatesAnAdjustmentWithTheCorrectAmount(int $giftCardAmount, int $orderTotal, int $expectedAdjustmentAmount): void
    {
        $giftCard = new GiftCard();
        $giftCard->setCode('gift_card');
        $giftCard->setInitialAmount($giftCardAmount); // 100 EUR
        $giftCard->setAmount($giftCardAmount); // 100 EUR

        $order = $this->createMock(OrderInterface::class);
        $order
            ->expects(self::once())
            ->method('getTotal')
            ->willReturn($orderTotal); // 50 EUR

        $adjustment = new Adjustment();

        $this->adjustmentFactory
            ->expects(self::once())
            ->method('createWithData')
            ->with(
                AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT,
                'gift_card',
                -$expectedAdjustmentAmount,
                false,
                [
                    'amount' => $expectedAdjustmentAmount,
                    'initial_amount' => $giftCardAmount,
                    'code' => 'gift_card',
                ]
            )
            ->willReturn($adjustment);

        self::assertSame($adjustment, $this->giftCardAdjustmentFactory->createNewForGiftCard($giftCard, $order));

        self::assertSame(GiftCardInterface::ADJUSTMENT_ORIGIN, $adjustment->getOriginCode());
    }

    public static function provideData(): iterable
    {
        yield 'Gift card amount is less than order total' => [5000, 10000, 5000];
        yield 'Gift card amount is equal to order total' => [10000, 10000, 10000];
        yield 'Gift card amount is greater than order total' => [15000, 10000, 10000];
    }
}
