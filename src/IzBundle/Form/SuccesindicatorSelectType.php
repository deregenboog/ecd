<?php

namespace IzBundle\Form;

use AppBundle\Form\BaseSelectType;
use IzBundle\Entity\Succesindicator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuccesindicatorSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Succesindicator::class,
            'label' => 'Succesindicatoren',
            'multiple' => true,
            'expanded' => true,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseSelectType::class;
    }
}
