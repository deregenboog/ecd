<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use IzBundle\Entity\IzProject;
use AppBundle\Form\StadsdeelFilterType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use IzBundle\Filter\IzDeelnemerSelectie;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\FilterType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use IzBundle\Entity\Doelstelling;
use IzBundle\Entity\Doelgroep;
use IzBundle\Entity\Hulpvraagsoort;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use IzBundle\Entity\MatchingKlant;
use IzBundle\Entity\MatchingVrijwilliger;

class MatchingVrijwilligerType extends AbstractType
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
                'label' => 'Belangstelling voor doelgroepen',
            ])
            ->add('hulpvraagsoorten', EntityType::class, [
                'class' => Hulpvraagsoort::class,
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'placeholder' => '',
                'label' => 'Belangstelling voor hulpvraagsoort(en)',
            ])
            ->add('voorkeurVoorNederlands', ChoiceType::class, [
                'label' => 'Voorkeur voor Nederlandstalige deelnemer',
                'expanded' => true,
                'required' => true,
                'choices' => [
                    'Ja' => 1,
                    'Nee' => 0,
                ],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MatchingVrijwilliger::class,
        ]);
    }
}
