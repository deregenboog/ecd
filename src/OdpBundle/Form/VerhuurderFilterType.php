<?php

namespace OdpBundle\Form;

use AppBundle\Form\FilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantFilterType;
use OdpBundle\Filter\VerhuurderFilter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateRangeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class VerhuurderFilterType extends AbstractType
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

        if (in_array('wpi', $options['enabled_filters'])) {
            $builder->add('wpi', CheckboxType::class, [
                'required' => false,
            ]);
        }

        if (in_array('ksgw', $options['enabled_filters'])) {
            $builder->add('ksgw', CheckboxType::class, [
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
            'data_class' => VerhuurderFilter::class,
            'enabled_filters' => [
                'klant' => ['id', 'naam', 'stadsdeel'],
                'aanmelddatum',
                'afsluitdatum',
                'wpi',
                'ksgw',
            ],
        ]);
    }
}
