<?php

namespace IzBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use IzBundle\Entity\IzHulpvraag;

class IzHulpvraagFilterType extends IzKoppelingFilterType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('izKlant', IzKlantFilterType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IzHulpvraag::class,
            'data' => null,
            'method' => 'GET',
        ]);
    }
}
