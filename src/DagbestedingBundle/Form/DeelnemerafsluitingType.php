<?php

namespace DagbestedingBundle\Form;

use DagbestedingBundle\Entity\Deelnemerafsluiting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeelnemerafsluitingType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deelnemerafsluiting::class,
        ]);
    }

    public function getParent(): ?string
    {
        return AfsluitingType::class;
    }
}
