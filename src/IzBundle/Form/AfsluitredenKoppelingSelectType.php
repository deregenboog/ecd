<?php

namespace IzBundle\Form;

use AppBundle\Form\BaseSelectType;
use IzBundle\Entity\AfsluitredenKoppeling;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AfsluitredenKoppelingSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => AfsluitredenKoppeling::class,
            'label' => 'Afsluitreden',
        ]);
    }

    public function getParent(): ?string
    {
        return BaseSelectType::class;
    }
}
