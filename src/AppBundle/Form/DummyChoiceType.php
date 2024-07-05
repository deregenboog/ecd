<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DummyChoiceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'disabled' => true,
            'mapped' => false,
            'dummy_label' => '',
            'choices' => function (Options $options) {
                return [$options['dummy_label'] => ''];
            },
        ]);
    }

    public function getParent(): ?string
    {
        return ChoiceType::class;
    }
}
