<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateTimeType;
use AppBundle\Form\BaseType;
use InloopBundle\Entity\Registratie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistratieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('binnen', AppDateTimeType::class)
            ->add('buiten', AppDateTimeType::class)
            ->add('kleding')
            ->add('maaltijd')
            ->add('activering')
            ->add('veegploeg')
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Registratie::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
