<?php

namespace HsBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use HsBundle\Entity\Betaling;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BetalingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datum', AppDateType::class)
            ->add('bedrag', MoneyType::class)
            ->add('referentie')
            ->add('info')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Betaling::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
