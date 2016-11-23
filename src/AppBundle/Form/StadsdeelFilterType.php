<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class StadsdeelFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam', ChoiceType::class, [
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
            ])
        ;
    }
}
