<?php

namespace IzBundle\Form;

use AppBundle\Form\BaseSelectType;
use IzBundle\Entity\BinnengekomenVia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BinnengekomenViaSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => BinnengekomenVia::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseSelectType::class;
    }
}
