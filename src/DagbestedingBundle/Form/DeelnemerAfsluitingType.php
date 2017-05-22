<?php

namespace DagbestedingBundle\Form;

use Symfony\Component\Form\AbstractType;
use DagbestedingBundle\Entity\DeelnemerAfsluiting;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeelnemerAfsluitingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeelnemerAfsluiting::class,
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
