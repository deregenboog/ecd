<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JaNeeType extends AbstractType
{
    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefaults([
            'expanded' => true,
            'choices' => [
                'Ja' => 1,
                'Nee' => 0,
            ],
        ]);
    }

    public function getParent(): ?string
    {
        return ChoiceType::class;
    }
}
