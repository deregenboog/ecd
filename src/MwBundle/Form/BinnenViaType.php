<?php

namespace MwBundle\Form;

use AppBundle\Form\BaseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class BinnenViaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
//            ->add('actief')
            ->add('submit', SubmitType::class)
        ;
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
