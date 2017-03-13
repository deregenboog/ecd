<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HsBundle\Entity\Klus;
use AppBundle\Form\AppDateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class KlusType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startdatum', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('datum', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('activiteit')
            ->add('medewerker')
            ->add('vrijwilligers')
            ->add('onHold')
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klus::class,
        ]);
    }
}
