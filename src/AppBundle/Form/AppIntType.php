<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppIntType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'widget' => 'single_text',
//            'format' => 'dd-MM-yyyy',
            'html5' => false,
        ]);
    }

    public function getParent(): ?string
    {
        return IntegerType::class;
    }
}
