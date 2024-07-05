<?php

namespace ClipBundle\Form;

use AppBundle\Form\BaseSelectType;
use ClipBundle\Entity\Vraagsoort;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VraagsoortSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Vraagsoort::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseSelectType::class;
    }
}
