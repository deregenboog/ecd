<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppTimeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'widget' => 'single_text',
            'attr' => ['placeholder' => 'uu:mm'],
            'html5' => false,
        ]);
    }

    public function getParent(): ?string
    {
        return TimeType::class;
    }
}
