<?php

declare(strict_types=1);

namespace Macbim\SyliusGiftCardsPlugin\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class GiftCardChannelConfigurationType extends AbstractResourceType
{
    public function __construct(
        string $dataClass,
        private readonly ChannelContextInterface $channelContext,
        array $validationGroups = [],
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $dateValidationCallback = static function ($value, ExecutionContextInterface $context) {
            if (null === $value) {
                return;
            }

            try {
                $check = new \DateTime($value);
            } catch (\DateMalformedStringException) {
                $context
                    ->buildViolation('macbim_sylius_gift_cards.gift_card_channel_configuration.expiration_delay_invalid')
                    ->setTranslationDomain('validators')
                    ->atPath('expirationDelay')
                    ->addViolation();
            }
        };

        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('enabled', CheckboxType::class, [
                'label' => 'macbim_sylius_gift_cards.form.gift_card.enabled',
            ])
            ->add('expirationDelay', TextType::class, [
                'label' => 'macbim_sylius_gift_cards.form.gift_card_channel_configuration.expiration_delay',
                'constraints' => [
                    new Callback($dateValidationCallback),
                ],
            ])
            ->add('channel', ChannelChoiceType::class, [
                'label' => 'sylius.ui.channel',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('channel', $this->channelContext->getChannel())
            ->setAllowedTypes('channel', ChannelInterface::class);
    }
}
