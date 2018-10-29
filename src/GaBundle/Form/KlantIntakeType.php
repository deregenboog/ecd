<?php

namespace GaBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\KlantType;
use AppBundle\Form\MedewerkerType;
use GaBundle\Entity\KlantIntake;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\AppTextareaType;

class KlantIntakeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $intake KlantIntake */
        $intake = $options['data'];

        if ($intake->getKlant() && $intake->getKlant()->getId()) {
            $builder->add('klant', DummyChoiceType::class, [
                'dummy_label' => $intake->getKlant(),
            ]);
        } else {
            $builder->add('klant', KlantType::class);
        }

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
            'data_class' => KlantIntake::class,
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
