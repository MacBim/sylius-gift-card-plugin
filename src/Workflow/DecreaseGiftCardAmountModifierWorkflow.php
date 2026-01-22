<?php

namespace Macbim\SyliusGiftCardsPlugin\Workflow;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Macbim\SyliusGiftCardsPlugin\Modifier\GiftCard\GiftCardAmountModifierInterface;

final class DecreaseGiftCardAmountModifierWorkflow extends AbstractAmountModifierWorkflow
{
    public function __construct(
        private readonly GiftCardAmountModifierInterface $giftCardAmountModifier,
    ) {}

    protected function doModifyAmount(GiftCardInterface $giftCard, int $amount): void
    {
        $this->giftCardAmountModifier->decreaseAmount($giftCard, $amount);
    }
}
