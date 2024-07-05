<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppTimeType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DagSelectType;
use InloopBundle\Entity\Locatietijd;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocatietijdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('dagVanDeWeek', DagSelectType::class)
            ->add('openingstijd', AppTimeType::class)
            ->add('sluitingstijd', AppTimeType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Locatietijd::class,
        ]);
    }
}
