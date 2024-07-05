<?php

namespace GaBundle\Form;

use AppBundle\Form\BaseType;
use GaBundle\Entity\ActiviteitAnnuleringsreden;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviteitAnnuleringsredenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ActiviteitAnnuleringsreden::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
