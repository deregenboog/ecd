<?php

namespace OekraineBundle\Form;

use AppBundle\Form\BaseType;
use OekraineBundle\Entity\Afsluitreden;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AfsluitredenType extends AbstractType
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
            'data_class' => Afsluitreden::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
