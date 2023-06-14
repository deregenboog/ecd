<?php

namespace OekraineBundle\Form;

use OekraineBundle\Filter\RegistratieHistoryFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistratieHistoryFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
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
