<?php

namespace BuurtboerderijBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\VrijwilligerFilterType as AppVrijwilligerFilterType;
use BuurtboerderijBundle\Filter\VrijwilligerFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (key_exists('vrijwilliger', $options['enabled_filters'])) {
            $builder->add('vrijwilliger', AppVrijwilligerFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['vrijwilliger'],
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
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VrijwilligerFilter::class,
            'data' => new VrijwilligerFilter(),
            'enabled_filters' => [
                'vrijwilliger' => ['id', 'naam', 'stadsdeel'],
                'aanmelddatum',
                'afsluitdatum',
                'filter',
                'download',
            ],
        ]);
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
