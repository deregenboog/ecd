<?php

namespace OdpBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType;
use OdpBundle\Filter\HuurderFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HuurderFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (in_array('automatischeIncasso', $options['enabled_filters'])) {
            $builder->add('automatischeIncasso', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Ja' => 1,
                    'Nee' => 0,
                ],
            ]);
        }

        if (in_array('inschrijvingWoningnet', $options['enabled_filters'])) {
            $builder->add('inschrijvingWoningnet', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Ja' => 1,
                    'Nee' => 0,
                ],
            ]);
        }

        if (in_array('waPolis', $options['enabled_filters'])) {
            $builder->add('waPolis', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Ja' => 1,
                    'Nee' => 0,
                ],
            ]);
        }

        if (in_array('wpi', $options['enabled_filters'])) {
            $builder->add('wpi', CheckboxType::class, [
                'required' => false,
            ]);
        }

        if (in_array('aanmelddatum', $options['enabled_filters'])) {
            $builder->add('aanmelddatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('afsluitdatum', $options['enabled_filters'])) {
            $builder->add('afsluitdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        $builder
            ->add('filter', SubmitType::class, ['label' => 'Filteren'])
            ->add('download', SubmitType::class, ['label' => 'Downloaden'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FilterType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HuurderFilter::class,
            'enabled_filters' => [
                'klant' => ['id', 'naam', 'stadsdeel'],
                'automatischeIncasso',
                'inschrijvingWoningnet',
                'waPolis',
                'aanmelddatum',
                'afsluitdatum',
                'wpi',
            ],
        ]);
    }
}
