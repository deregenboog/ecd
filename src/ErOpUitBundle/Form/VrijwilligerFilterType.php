<?php

namespace ErOpUitBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\VrijwilligerFilterType as AppVrijwilligerFilterType;
use ErOpUitBundle\Filter\VrijwilligerFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (key_exists('vrijwilliger', $options['enabled_filters'])) {
            $builder->add('vrijwilliger', AppVrijwilligerFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['vrijwilliger'],
            ]);
        }

        if (in_array('inschrijfdatum', $options['enabled_filters'])) {
            $builder->add('inschrijfdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('uitschrijfdatum', $options['enabled_filters'])) {
            $builder->add('uitschrijfdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VrijwilligerFilter::class,
            'data' => new VrijwilligerFilter(),
            'enabled_filters' => [
                'vrijwilliger' => ['id', 'naam', 'stadsdeel'],
                'inschrijfdatum',
                'uitschrijfdatum',
                'filter',
                'download',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FilterType::class;
    }
}
