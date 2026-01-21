<?php

namespace Macbim\SyliusGiftCardsPlugin\Workflow;

use Doctrine\ORM\EntityManagerInterface;
use Macbim\SyliusGiftCardsPlugin\Factory\GiftCardFactoryInterface;
use Macbim\SyliusGiftCardsPlugin\Model\OrderInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderItemUnitInterface;

final class CreateGiftCard
{
    public function __construct(
        private readonly GiftCardFactoryInterface $giftCardFactory,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(OrderInterface $order): void
    {
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();

        $currencyCode = $order->getCurrencyCode() ?? $channel->getBaseCurrency()?->getCode();
        if (null === $currencyCode) {
            throw new \RuntimeException('No currency code found for order');
        }

        /** @var CustomerInterface|null $customer */
        $customer = $order->getCustomer();
        if (null === $customer) {
            throw new \RuntimeException('The order must have a customer to create gift cards.');
        }

        foreach ($order->getGiftCardsOrderItems() as $orderItem) {
            /** @var OrderItemUnitInterface $unit */
            foreach ($orderItem->getUnits() as $unit) {
                $giftCard = $this->giftCardFactory->createNewEnabledForOrderItemUnit($unit, $channel, $currencyCode);
                $giftCard->setCustomer($customer);

                $this->entityManager->persist($giftCard);
            }
        }

        $this->entityManager->flush();
    }
}
