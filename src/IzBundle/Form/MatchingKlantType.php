<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use IzBundle\Entity\Doelgroep;
use IzBundle\Entity\Hulpvraagsoort;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use IzBundle\Entity\MatchingKlant;
use AppBundle\Form\BaseType;

class MatchingKlantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('info', TextareaType::class, [
                'label' => 'Matchingsinformatie',
                'attr' => ['cols' => 80, 'rows' => 5],
            ])
            ->add('doelgroepen', EntityType::class, [
                'class' => Doelgroep::class,
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'placeholder' => '',
            ])
            ->add('hulpvraagsoort', EntityType::class, [
                'class' => Hulpvraagsoort::class,
                'expanded' => true,
                'required' => true,
            ])
            ->add('spreektNederlands', ChoiceType::class, [
                'label' => 'Spreekt Nederlands',
                'expanded' => true,
                'required' => true,
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
            'data_class' => MatchingKlant::class,
        ]);
    }

    public function getParent()
    {
        return BaseType::class;
    }
}
