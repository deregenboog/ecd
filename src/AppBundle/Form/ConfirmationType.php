<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConfirmationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('yes', SubmitType::class, ['label' => 'Ja'])
            ->add('no', SubmitType::class, ['label' => 'Nee'])
            ->add('referer', HiddenType::class, ['mapped' => false])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return \AppBundle\Form\BaseType::class;
    }
}
