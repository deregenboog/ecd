<?php

namespace DagbestedingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DagbestedingBundle\Entity\TrajectAfsluiting;

class TrajectAfsluitingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrajectAfsluiting::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return AfsluitingType::class;
    }
}
