<?php

namespace ClipBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtniciteitSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => [
                'Antilliaans/Arubaans' => 'Antilliaans/Arubaans',
                'Ghanees' => 'Ghanees',
                'Marokkaans' => 'Marokkaans',
                'Nederlands' => 'Nederlands',
                'Surinaams' => 'Surinaams',
                'Turks' => 'Turks',
                'Overig' => 'Overig',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
