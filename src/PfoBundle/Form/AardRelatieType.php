<?php

namespace PfoBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use PfoBundle\Entity\AardRelatie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AardRelatieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('startdatum', AppDateType::class, [
                'required' => false,
            ])
            ->add('einddatum', AppDateType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AardRelatie::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
