<?php

namespace InloopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use InloopBundle\Entity\Locatie;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Form\AppDateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use InloopBundle\Entity\Registratie;

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
    public function getParent()
    {
        return BaseType::class;
    }
}
