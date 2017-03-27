<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StadsdeelFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Stadsdeel',
            'required' => false,
            'choices' => [
                'Amstelveen' => 'Amstelveen',
                'Centrum' => 'Centrum',
                'Diemen' => 'Diemen',
                'Nieuw-West' => 'Nieuw-West',
                'Noord' => 'Noord',
                'Oost' => 'Oost',
                'West' => 'West',
                'Westpoort' => 'Westpoort',
                'Zuid' => 'Zuid',
                'Zuidoost' => 'Zuidoost',
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
