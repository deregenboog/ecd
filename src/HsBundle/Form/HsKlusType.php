<?php

namespace HsBundle\Form;

use AppBundle\Form\BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HsBundle\Entity\HsKlus;
use AppBundle\Form\AppDateType;

class HsKlusType extends BaseType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startdatum', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('datum', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('hsActiviteit', null, ['label' => 'Activiteit'])
            ->add('medewerker')
            ->add('hsVrijwilligers', null, ['label' => 'Vrijwilligers'])
            ->add('onHold')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HsKlus::class,
        ]);
    }
}
