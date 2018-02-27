<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use InloopBundle\Filter\RegistratieFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use InloopBundle\Filter\RegistratieHistoryFilter;

class RegistratieHistoryFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return RegistratieFilterType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RegistratieHistoryFilter::class,
            'enabled_filters' => [
                'klant' => ['naam'],
                'binnen',
                'buiten',
                'maaltijd',
                'activering',
                'kleding',
                'veegploeg',
                'filter',
            ],
        ]);
    }
}
