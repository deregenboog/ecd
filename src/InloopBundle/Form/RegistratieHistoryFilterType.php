<?php

namespace InloopBundle\Form;

use InloopBundle\Filter\RegistratieHistoryFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'klant' => ['voornaam', 'achternaam'],
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
