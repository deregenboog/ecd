<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ConfirmationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('yes', 'Symfony\Component\Form\Extension\Core\Type\HiddenType', ['data' => 1])
            ->add('referer', HiddenType::class, ['mapped' => false])
        ;
    }
}
