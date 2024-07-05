<?php

namespace MwBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use MwBundle\Entity\Deelname;
use MwBundle\Entity\Training;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeelnameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datum', AppDateType::class, ['data' => new \DateTime('now')])
            ->add('training', EntityType::class, ['class' => Training::class])
            ->add('overig', null, ['required' => false])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deelname::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
