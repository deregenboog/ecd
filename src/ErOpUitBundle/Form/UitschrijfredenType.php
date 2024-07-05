<?php

namespace ErOpUitBundle\Form;

use AppBundle\Form\BaseType;
use ErOpUitBundle\Entity\Uitschrijfreden;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UitschrijfredenType extends AbstractType
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
            'data_class' => Uitschrijfreden::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
