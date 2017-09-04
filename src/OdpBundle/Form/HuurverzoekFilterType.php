<?php

namespace OdpBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use OdpBundle\Filter\HuurverzoekFilter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class HuurverzoekFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'required' => false,
            ]);
        }

        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }
        if (in_array('startdatum', $options['enabled_filters'])) {
            $builder->add('startdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('afsluitdatum', $options['enabled_filters'])) {
            $builder->add('afsluitdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('actief', $options['enabled_filters'])) {
            $builder->add('actief', CheckboxType::class, [
                'required' => false,
                'label' => 'Actieve huurverzoeken',
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
            'data_class' => HuurverzoekFilter::class,
            'enabled_filters' => [
                'id',
                'klant' => ['naam', 'stadsdeel'],
                'startdatum',
                'afsluitdatum',
                'actief',
            ],
        ]);
    }
}
