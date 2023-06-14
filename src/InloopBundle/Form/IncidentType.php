<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\JaNeeType;
use InloopBundle\Entity\Incident;
use InloopBundle\Entity\Schorsing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncidentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('locatie', LocatieSelectType::class, [
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('datum', AppDateType::class, [
                'label' => 'Datum',
            ])
            ->add('politie', JaNeeType::class, [
                'label' => 'Is de politie gebeld?',
            ])
            ->add('ambulance', JaNeeType::class, [
                'label' => 'Is de ambulance gebeld?',
            ])
            ->add('crisisdienst', JaNeeType::class, [
                'label' => 'Is de crisisdienst gebeld?',
            ])
            ->add('opmerking', null, ['required' => false])

            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return BaseType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Incident::class,
//            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
