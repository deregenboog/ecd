<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HsBundle\Entity\HsKlus;
use AppBundle\Form\AppDateType;

class HsKlusType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datum', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('hsActiviteit')
            ->add('medewerker')
            ->add('hsVrijwilligers', null, ['label' => 'Vrijwilligers']);
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
