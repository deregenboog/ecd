<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Filter\KlantFilter;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class KlantFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Klantnummer'],
            ])
            ->add('naam', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Naam klant'],
            ])
            ->add('geboortedatum', BirthdayType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => ['placeholder' => 'dd-mm-jjjj'],
            ])
            ->add('stadsdeel', StadsdeelFilterType::class, [
                'required' => false,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => KlantFilter::class,
            'method' => 'GET',
        ]);
    }
}
