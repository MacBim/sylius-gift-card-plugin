<?php

declare(strict_types=1);

namespace Unit\Workflow;

use Doctrine\ORM\EntityManagerInterface;
use Macbim\SyliusGiftCardsPlugin\Factory\GiftCardFactoryInterface;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCard;
use Macbim\SyliusGiftCardsPlugin\Workflow\CreateGiftCard;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\Model\OrderItemUnit;
use Sylius\Component\Core\Model\ProductVariant;
use Tests\Macbim\SyliusGiftCardsPlugin\Model\Order;
use Tests\Macbim\SyliusGiftCardsPlugin\Model\Product;

#[CoversClass(CreateGiftCard::class)]
class CreateGiftCardTest extends TestCase
{
    private GiftCardFactoryInterface $giftCardFactory;

    private EntityManagerInterface $entityManager;

    private CreateGiftCard $workflow;

    protected function setUp(): void
    {
        $this->giftCardFactory = $this->createMock(GiftCardFactoryInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->workflow = new CreateGiftCard(
            $this->giftCardFactory,
            $this->entityManager
        );
    }

    public function testItThrowsAnExceptionIfNoCurrencyCanBeFoundOnTheOrderOrTheChannel(): void
    {
        $order = new Order();
        $order->setCurrencyCode(null);

        $channel = new Channel();
        $channel->setBaseCurrency(null);
        $order->setChannel($channel);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No currency code found for order');

        ($this->workflow)($order);
    }

    public function testItThrowsAnExceptionIfTheOrderHasNoCustomer(): void
    {
        $order = new Order();
        $order->setCurrencyCode('EUR');
        $order->setCustomer(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The order must have a customer to create gift cards.');

        ($this->workflow)($order);
    }

    public function testItDoesNothingIfTheOrderHasNoBoughtGiftCards(): void
    {
        $order = new Order();
        $order->setCurrencyCode('EUR');

        $channel = new Channel();
        $channel->setBaseCurrency(null);
        $order->setChannel($channel);

        $customer = new Customer();
        $order->setCustomer($customer);

        $this->giftCardFactory
            ->expects(self::never())
            ->method('createNewEnabledForOrderItemUnit');

        $this->entityManager
            ->expects(self::never())
            ->method('persist');

        ($this->workflow)($order);
    }

    public function testItCreatesGiftCards(): void
    {
        $order = new Order();
        $order->setCurrencyCode('EUR');

        $channel = new Channel();
        $channel->setBaseCurrency(null);
        $order->setChannel($channel);

        $customer = new Customer();
        $order->setCustomer($customer);

        $productAsGiftCard = new Product();
        $productAsGiftCard->setIsGiftCard(true);

        $variant = new ProductVariant();
        $productAsGiftCard->addVariant($variant);

        $orderItem = new OrderItem();
        $orderItem->setVariant($variant);
        $orderItemUnit = new OrderItemUnit($orderItem);

        $order->addItem($orderItem);

        $giftCard = new GiftCard();
        $this->giftCardFactory
            ->expects(self::once())
            ->method('createNewEnabledForOrderItemUnit')
            ->with($orderItemUnit, $channel, 'EUR')
            ->willReturn($giftCard);

        $this->entityManager
            ->expects(self::once())
            ->method('persist')
            ->with($giftCard);

        ($this->workflow)($order);

        self::assertSame($customer, $giftCard->getCustomer());
    }
}
