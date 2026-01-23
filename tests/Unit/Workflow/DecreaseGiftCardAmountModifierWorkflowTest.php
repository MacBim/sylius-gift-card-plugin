<?php

declare(strict_types=1);

namespace Unit\Workflow;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCard;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Modifier\GiftCard\GiftCardAmountModifierInterface;
use Macbim\SyliusGiftCardsPlugin\Workflow\DecreaseGiftCardAmountModifierWorkflow;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Adjustment;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Tests\Macbim\SyliusGiftCardsPlugin\Model\Order;

#[CoversClass(DecreaseGiftCardAmountModifierWorkflow::class)]
class DecreaseGiftCardAmountModifierWorkflowTest extends TestCase
{
    private GiftCardAmountModifierInterface $amountModifier;

    private DecreaseGiftCardAmountModifierWorkflow $workflow;

    protected function setUp(): void
    {
        $this->amountModifier = $this->createMock(GiftCardAmountModifierInterface::class);

        $this->workflow = new DecreaseGiftCardAmountModifierWorkflow(
            $this->amountModifier
        );
    }

    public function testItDoesNothingIfTheOrderHasNoAppliedGiftCards(): void
    {
        $order = new Order();

        self::assertCount(0, $order->getAppliedGiftCards());

        $this->amountModifier
            ->expects(self::never())
            ->method('decreaseAmount');

        ($this->workflow)($order);
    }

    public function testItDoesNothingIfTheOrderHasSomeAppliedGiftCardsButNoAdjustments(): void
    {
        $giftCard = new GiftCard();
        $order = new Order();
        $order->addAppliedGiftCard($giftCard);

        $this->amountModifier
            ->expects(self::never())
            ->method('decreaseAmount');

        ($this->workflow)($order);
    }

    public function testItDoesNothingIfTheOrderHasSomeAppliedGiftCardsButAnAdjustmentWithTheWrongType(): void
    {
        $giftCard = new GiftCard();
        $order = new Order();
        $order->addAppliedGiftCard($giftCard);

        $adjustment = new Adjustment();
        $adjustment->setType('foo_bar');

        $order->addAdjustment($adjustment);

        $this->amountModifier
            ->expects(self::never())
            ->method('decreaseAmount');

        ($this->workflow)($order);
    }

    public function testItDoesNothingIfTheOrderHasSomeAppliedGiftCardsButAnAdjustmentWithTheWrongOrigin(): void
    {
        $giftCard = new GiftCard();
        $order = new Order();
        $order->addAppliedGiftCard($giftCard);

        $adjustment = new Adjustment();
        $adjustment->setType(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT);
        $adjustment->setOriginCode('foo_bar');

        $order->addAdjustment($adjustment);

        $this->amountModifier
            ->expects(self::never())
            ->method('decreaseAmount');

        ($this->workflow)($order);
    }

    public function testItDoesNothingIfTheAdjustmentHasAAmountOfZero(): void
    {
        $giftCard = new GiftCard();
        $order = new Order();
        $order->addAppliedGiftCard($giftCard);

        $adjustment = new Adjustment();
        $adjustment->setType(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT);
        $adjustment->setOriginCode(GiftCardInterface::ADJUSTMENT_ORIGIN);
        $adjustment->setAmount(0);

        $order->addAdjustment($adjustment);

        $this->amountModifier
            ->expects(self::never())
            ->method('decreaseAmount');

        ($this->workflow)($order);
    }

    public function testItThrowsAnExceptionIfTheOrderHasAnAdjustmentForAGiftCardThatIsNotAppliedToIt(): void
    {
        $giftCard = new GiftCard();
        $giftCard->setCode('foo_bar');

        $order = new Order();
        $order->addAppliedGiftCard($giftCard);

        $adjustment = new Adjustment();
        $adjustment->setType(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT);
        $adjustment->setOriginCode(GiftCardInterface::ADJUSTMENT_ORIGIN);
        $adjustment->setAmount(1000); // 10 EUR
        $adjustment->setDetails([
            'code' => 'bar_baz',
        ]);

        $order->addAdjustment($adjustment);

        $this->amountModifier
            ->expects(self::never())
            ->method('decreaseAmount');

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Order has an adjustment for a gift card (bar_baz) that was not applied');

        ($this->workflow)($order);
    }

    public function testItDoesModifyTheAmount(): void
    {
        $giftCard = new GiftCard();
        $giftCard->setCode('foo_bar');

        $order = new Order();
        $order->addAppliedGiftCard($giftCard);

        $adjustment = new Adjustment();
        $adjustment->setType(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT);
        $adjustment->setOriginCode(GiftCardInterface::ADJUSTMENT_ORIGIN);
        $adjustment->setAmount(-1000); // 10 EUR
        $adjustment->setDetails([
            'code' => 'foo_bar',
        ]);

        $order->addAdjustment($adjustment);

        $this->amountModifier
            ->expects(self::once())
            ->method('decreaseAmount')
            ->with($giftCard, 1000);

        ($this->workflow)($order);
    }
}
