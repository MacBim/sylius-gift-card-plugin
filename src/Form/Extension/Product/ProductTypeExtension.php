<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Form\Extension\Product;

use Sylius\Bundle\ProductBundle\Form\Type\ProductType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isGiftCard', CheckboxType::class, [
                'label' => 'macbim_sylius_gift_cards.form.product.is_gift_card'
            ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [ProductType::class];
    }
}
