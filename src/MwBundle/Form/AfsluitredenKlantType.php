<?php

namespace MwBundle\Form;

use AppBundle\Form\BaseType;
use MwBundle\Entity\AfsluitredenKlant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AfsluitredenKlantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('gewicht')
            ->add('land')
            ->add('actief')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AfsluitredenKlant::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
