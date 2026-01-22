<?php

declare(strict_types=1);

namespace Unit\Checker;

use Macbim\SyliusGiftCardsPlugin\Checker\GiftCard\CompositeGiftCardEligibilityChecker;
use Macbim\SyliusGiftCardsPlugin\Checker\GiftCard\GiftCardEligibilityCheckerInterface;
use Macbim\SyliusGiftCardsPlugin\Model\GiftCard;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Macbim\SyliusGiftCardsPlugin\Model\Order;

#[CoversClass(CompositeGiftCardEligibilityChecker::class)]
class CompositeGiftCardEligibilityCheckerTest extends TestCase
{
    private GiftCardEligibilityCheckerInterface $firstChecker;

    private GiftCardEligibilityCheckerInterface $secondChecker;

    private GiftCardEligibilityCheckerInterface $thirdChecker;

    private CompositeGiftCardEligibilityChecker $compositeChecker;

    protected function setUp(): void
    {
        $this->firstChecker = $this->createMock(GiftCardEligibilityCheckerInterface::class);
        $this->secondChecker = $this->createMock(GiftCardEligibilityCheckerInterface::class);
        $this->thirdChecker = $this->createMock(GiftCardEligibilityCheckerInterface::class);

        $this->compositeChecker = new CompositeGiftCardEligibilityChecker([
            $this->firstChecker,
            $this->secondChecker,
            $this->thirdChecker,
        ]);
    }

    public function testItStopsAtTheFirstCheckerThatReturnsFalse(): void
    {
        $giftCard = new GiftCard();
        $order = new Order();

        $this->firstChecker
            ->expects(self::once())
            ->method('isEligible')
            ->with($giftCard, $order)
            ->willReturn(false);

        $this->secondChecker
            ->expects(self::never())
            ->method('isEligible');

        $this->thirdChecker
            ->expects(self::never())
            ->method('isEligible');

        self::assertFalse($this->compositeChecker->isEligible($giftCard, $order));
    }

    public function testItReturnsFalseIfThereAreNoCheckers(): void
    {
        $emptyCompositeChecker = new CompositeGiftCardEligibilityChecker([]);

        self::assertFalse($emptyCompositeChecker->isEligible(new GiftCard(), new Order()));
    }
}
