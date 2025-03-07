<?php

namespace DagbestedingBundle\Form;

use DagbestedingBundle\Entity\Trajectafsluiting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrajectafsluitingType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trajectafsluiting::class,
        ]);
    }

    public function getParent(): ?string
    {
        return AfsluitingType::class;
    }
}
