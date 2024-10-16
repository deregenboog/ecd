<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('yes', SubmitType::class, ['label' => 'Ja'])
            ->add('no', SubmitType::class, ['label' => 'Nee'])
            ->add('referer', HiddenType::class, ['mapped' => false])
        ;
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
