<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Form\Type;

use Macbim\SyliusGiftCardsPlugin\Model\GiftCardInterface;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\CustomerBundle\Form\Type\CustomerChoiceType;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class GiftCardType extends AbstractResourceType
{
    public function __construct(
        string $dataClass,
        private readonly RepositoryInterface $currencyRepository,
        array $validationGroups = [],
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $builder->getData();

        $currencyCode = $data instanceof GiftCardInterface ? $data->getCurrencyCode() : $options['currency'];

        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('enabled', CheckboxType::class, [
                'label' => 'macbim_sylius_gift_cards.form.gift_card.enabled',
            ])
            ->add('amount', MoneyType::class, [
                'currency' => $currencyCode,
                'label' => 'macbim_sylius_gift_cards.form.gift_card.amount',
            ])
            ->add('currencyCode', ChoiceType::class, [
                'label' => 'sylius.ui.currency',
                'choices' => $this->currencyRepository->findAll(),
                'choice_label' => 'code',
                'choice_value' => 'code',
            ])
            ->add('expiresAt', DateTimeType::class, [
                'required' => false,
                'label' => 'macbim_sylius_gift_cards.form.gift_card.expires_at',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->add('channel', ChannelChoiceType::class, [
                'label' => 'sylius.ui.channel',
            ])
            ->add('customer', CustomerChoiceType::class, [
                'label' => 'macbim_sylius_gift_cards.form.gift_card.customer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(['currency' => 'EUR'])
            ->setAllowedTypes('currency', ['string']);
    }
}
