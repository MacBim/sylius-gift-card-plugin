<?php

declare(strict_types=1);

namespace Unit\Workflow;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCard;
use Macbim\SyliusGiftCardsPlugin\Repository\GiftCardRepositoryInterface;
use Macbim\SyliusGiftCardsPlugin\Workflow\DisableGiftCardsOnRefund;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Macbim\SyliusGiftCardsPlugin\Model\Order;

#[CoversClass(DisableGiftCardsOnRefund::class)]
class DisableGiftCardsOnRefundTest extends TestCase
{
    private GiftCardRepositoryInterface $giftCardRepository;

    private DisableGiftCardsOnRefund $workflow;

    protected function setUp(): void
    {
        $this->giftCardRepository = $this->createMock(GiftCardRepositoryInterface::class);

        $this->workflow = new DisableGiftCardsOnRefund(
            $this->giftCardRepository
        );
    }

    public function testItDoesNothingIfTheOrderHasCreatedNoGiftCards(): void
    {
        $order = new Order();

        $this->giftCardRepository
            ->expects(self::once())
            ->method('findCreatedByOrder')
            ->with($order)
            ->willReturn([]);

        ($this->workflow)($order);
    }

    public function testItDisablesTheGiftCards(): void
    {
        $order = new Order();

        $giftCard1 = new GiftCard();
        $giftCard1->setEnabled(true);

        $giftCard2 = new GiftCard();
        $giftCard2->setEnabled(true);

        $this->giftCardRepository
            ->expects(self::once())
            ->method('findCreatedByOrder')
            ->with($order)
            ->willReturn([$giftCard1, $giftCard2]);

        ($this->workflow)($order);

        self::assertFalse($giftCard1->isEnabled());
        self::assertFalse($giftCard2->isEnabled());
    }
}
