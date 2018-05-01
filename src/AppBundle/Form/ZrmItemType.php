<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ZrmItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'expanded' => true,
            'choices' => [
                '1 - acute problematiek' => 1,
                '2 - niet zelfredzaam' => 2,
                '3 - beperkt zelfredzaam' => 3,
                '4 - voldoende zelfredzaam' => 4,
                '5 - volledig zelfredzaam' => 5,
                'onbesproken' => 99,
            ],
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
