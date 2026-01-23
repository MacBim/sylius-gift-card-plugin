<?php

declare(strict_types=1);

namespace Functional\Processor;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCard;
use Macbim\SyliusGiftCardsPlugin\Processor\GiftCardOrderProcessor;
use PHPUnit\Framework\Attributes\CoversClass;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\Model\OrderItemUnit;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Macbim\SyliusGiftCardsPlugin\Model\Order;

#[CoversClass(GiftCardOrderProcessor::class)]
class GiftCardOrderProcessorTest extends KernelTestCase
{
    private GiftCardOrderProcessor $processor;

    protected function setUp(): void
    {
        $this->processor = self::getContainer()->get('macbim_sylius_gift_cards.order_processing.gift_card_order_processor');
    }

    public function testItCorrectlyProcessesTheOrder(): void
    {
        $firstAppliedGiftCard = new GiftCard();
        $firstAppliedGiftCard->setCode('first_gift_card');
        $firstAppliedGiftCard->setAmount(1000); // 10 EUR

        $secondAppliedGiftCard = new GiftCard();
        $secondAppliedGiftCard->setCode('second_gift_card');
        $secondAppliedGiftCard->setAmount(3000); // 30 EUR

        $orderItem = new OrderItem();
        $orderItem->setUnitPrice(5000); // 50 EUR

        $orderItemUnit = new OrderItemUnit($orderItem);

        $order = new Order();
        $order->addItem($orderItem);
        $order
            ->addAppliedGiftCard($firstAppliedGiftCard)
            ->addAppliedGiftCard($secondAppliedGiftCard);

        $this->processor->process($order);

        self::assertCount(2, $order->getAdjustments(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT));
        self::assertEquals(-4000, $order->getAdjustmentsTotalRecursively(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT));

        self::assertEquals(1000, $order->getTotal());
    }
}
