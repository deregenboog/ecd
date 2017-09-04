<?php

namespace DagbestedingBundle\Form;

use Symfony\Component\Form\AbstractType;
use DagbestedingBundle\Entity\Deelnemerafsluiting;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeelnemerafsluitingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deelnemerafsluiting::class,
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
