<?php

namespace GaBundle\Form;

use AppBundle\Form\BaseType;
use GaBundle\Entity\Activiteit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviteitCancelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('annuleringsreden')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activiteit::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
