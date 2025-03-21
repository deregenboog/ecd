<?php

namespace InloopBundle\Form;

use InloopBundle\Filter\RegistratieHistoryFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistratieHistoryFilterType extends AbstractType
{
    public function getParent(): ?string
    {
        return RegistratieFilterType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RegistratieHistoryFilter::class,
            'enabled_filters' => [
                'klant' => ['voornaam', 'achternaam'],
                'binnen',
                'buiten',
                'douche',
                'maaltijd',
                'activering',
                'kleding',
                'veegploeg',
                'mw',
                'filter',
            ],
        ]);
    }
}
