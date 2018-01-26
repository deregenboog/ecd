<?php

namespace GaBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use GaBundle\Entity\Intake;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntakeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $intake Intake */
        $intake = $options['data'];

        $builder
            ->add('intakedatum', AppDateType::class)
            ->add('gezinMetKinderen')
            ->add('medewerker', MedewerkerType::class)
            ->add('gespreksverslag', AppTextareaType::class, [
                'required' => false,
            ])
            ->add('ondernemen', ChoiceType::class, [
                'label' => 'Zou je het leuk vinden om iedere week met iemand samen iets te ondernemen?',
                'expanded' => true,
                'choices' => [
                    'Ja' => 1,
                    'Nee' => 0,
                ],
            ])
            ->add('overdag', ChoiceType::class, [
                'label' => 'Zou je het leuk vinden om overdag iets te doen te hebben?',
                'expanded' => true,
                'choices' => [
                    'Ja' => 1,
                    'Nee' => 0,
                ],
            ])
            ->add('ontmoeten', ChoiceType::class, [
                'label' => 'Zou je een plek in de buurt willen hebben waar je iedere dag koffie kan drinken en mensen kan ontmoeten?',
                'expanded' => true,
                'choices' => [
                    'Ja' => 1,
                    'Nee' => 0,
                ],
            ])
            ->add('regelzaken', ChoiceType::class, [
                'label' => 'Heeft u hulp nodig met regelzaken?',
                'expanded' => true,
                'choices' => [
                    'Ja' => 1,
                    'Nee' => 0,
                ],
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intake::class,
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
