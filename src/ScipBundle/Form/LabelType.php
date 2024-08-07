<?php

namespace ScipBundle\Form;

use AppBundle\Form\BaseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class LabelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam', null, [
                'required' => true,
            ])
            ->add('actief', CheckboxType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
