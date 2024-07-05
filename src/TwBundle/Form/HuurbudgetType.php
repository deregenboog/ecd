<?php

namespace TwBundle\Form;

use AppBundle\Form\BaseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Entity\Huurbudget;

class HuurbudgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('minVal')
            ->add('maxVal')
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Huurbudget::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
