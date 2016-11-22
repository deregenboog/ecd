<?php

namespace IzBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use IzBundle\Entity\IzHulpaanbod;

class IzHulpaanbodFilterType extends IzKoppelingFilterType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('izVrijwilliger', IzVrijwilligerFilterType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IzHulpaanbod::class,
            'data' => null,
            'method' => 'GET',
        ]);
    }
}
