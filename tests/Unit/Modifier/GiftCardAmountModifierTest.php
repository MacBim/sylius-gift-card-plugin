<?php

declare(strict_types=1);

namespace Unit\Modifier;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCard;
use Macbim\SyliusGiftCardsPlugin\Modifier\GiftCard\GiftCardAmountModifier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(GiftCardAmountModifier::class)]
class GiftCardAmountModifierTest extends TestCase
{
    private GiftCardAmountModifier $modifier;

    protected function setUp(): void
    {
        $this->modifier = new GiftCardAmountModifier();
    }

    public function testItDisablesTheGiftCardAndSetItsAmountToZeroIfTheAmountIsNotEnough(): void
    {
        $giftCard = new GiftCard();
        $giftCard->setEnabled(true);
        $giftCard->setAmount(1000); // 10 EUR

        $this->modifier->decreaseAmount($giftCard, 2000); // 20 EUR

        self::assertFalse($giftCard->isEnabled());
        self::assertEquals(0, $giftCard->getAmount());
    }

    public function testItDoesNotDisablesTheGiftCardIfTheAmountOnItIsEnough(): void
    {
        $giftCard = new GiftCard();
        $giftCard->setEnabled(true);
        $giftCard->setAmount(1000); // 10 EUR

        $this->modifier->decreaseAmount($giftCard, 600); // 6 EUR

        self::assertTrue($giftCard->isEnabled());
        self::assertEquals(400, $giftCard->getAmount());
    }

    public function testItCannotIncreaseTheGiftCardAmountAboveItsInitialAmount(): void
    {
        $giftCard = new GiftCard();
        $giftCard->setInitialAmount(3000); // 30 EUR
        $giftCard->setAmount(1000); // 10 EUR

        $this->modifier->increaseAmount($giftCard, 5000); // 50 EUR

        self::assertEquals(3000, $giftCard->getAmount());
    }

    public function testItCorrectlyIncreasesTheGiftCardAmount(): void
    {
        $giftCard = new GiftCard();
        $giftCard->setInitialAmount(3000); // 30 EUR
        $giftCard->setAmount(1000); // 10 EUR

        $this->modifier->increaseAmount($giftCard, 1000); // 10 EUR

        self::assertEquals(2000, $giftCard->getAmount());
    }

    public function testItThrowsALogicExceptionIfTheGiftCardHasNoInitialAmount(): void
    {
        $giftCard = new GiftCard();
        $giftCard->setCode('empty_gift_card');
        $giftCard->setInitialAmount(null);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Gift card "empty_gift_card" has no initial amount.');

        $this->modifier->increaseAmount($giftCard, 1000); // 10 EUR
    }
}
