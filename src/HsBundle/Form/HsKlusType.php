<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HsBundle\Entity\HsKlus;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;

class HsKlusType extends AbstractType
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

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
