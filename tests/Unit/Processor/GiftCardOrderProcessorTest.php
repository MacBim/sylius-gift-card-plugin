<?php

declare(strict_types=1);

namespace Unit\Processor;

use Macbim\SyliusGiftCardsPlugin\Factory\GiftCard\GiftCardAdjustmentFactoryInterface;
use Macbim\SyliusGiftCardsPlugin\Processor\GiftCardOrderProcessor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\Order as BaseOrder;
use Tests\Macbim\SyliusGiftCardsPlugin\Model\Order;

#[CoversClass(GiftCardOrderProcessor::class)]
class GiftCardOrderProcessorTest extends TestCase
{
    private GiftCardAdjustmentFactoryInterface $giftCardAdjustmentFactory;

    private GiftCardOrderProcessor $orderProcessor;

    protected function setUp(): void
    {
        $this->giftCardAdjustmentFactory = $this->createMock(GiftCardAdjustmentFactoryInterface::class);

        $this->orderProcessor = new GiftCardOrderProcessor($this->giftCardAdjustmentFactory);
    }

    public function testItDoesNothingIfTheOrderDoesNotHaveTheCorrectType(): void
    {
        $order = new BaseOrder();

        $this->giftCardAdjustmentFactory
            ->expects(self::never())
            ->method('createNewForGiftCard');

        $this->orderProcessor->process($order);

        self::assertCount(0, $order->getAdjustmentsRecursively(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT));
    }

    public function testItDoesNothingIfTheOrderHasNoAppliedGiftCards(): void
    {
        $order = new Order();

        self::assertCount(0, $order->getAppliedGiftCards());

        $this->giftCardAdjustmentFactory
            ->expects(self::never())
            ->method('createNewForGiftCard');

        $this->orderProcessor->process($order);

        self::assertCount(0, $order->getAdjustmentsRecursively(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT));
    }
}
