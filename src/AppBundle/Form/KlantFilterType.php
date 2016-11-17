<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;

class KlantFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', null, ['required' => false])
            ->add('voornaam', null, ['required' => false])
            ->add('achternaam', null, ['required' => false])
            ->add('geboortedatum', null, ['widget' => 'single_text', 'format' => 'dd-MM-yyyy'])
            ->add('werkgebied', null, ['required' => false])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klant::class,
            'data' => null,
        ]);
    }
}
