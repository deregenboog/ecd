<?php

namespace IzBundle\Form;

use AppBundle\Form\BaseType;
use IzBundle\Entity\BinnengekomenVia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BinnengekomenViaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('actief')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => BinnengekomenVia::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
