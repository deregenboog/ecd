<?php

namespace TwBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use AppBundle\Form\StadsdeelSelectType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use TwBundle\Entity\Dagbesteding;
use TwBundle\Entity\Huisdieren;
use TwBundle\Entity\IntakeStatus;
use TwBundle\Entity\Klant;
use TwBundle\Entity\Regio;
use TwBundle\Entity\Ritme;
use TwBundle\Entity\Roken;
use TwBundle\Entity\Softdrugs;
use TwBundle\Entity\Traplopen;
use TwBundle\Entity\Verhuurder;
use PfoBundle\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Filter\KlantFilter;

class KlantFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('appKlant', $options['enabled_filters'])) {
            $builder->add('appKlant', AppKlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['appKlant'],

            ]);
            StadsdeelSelectType::$showOnlyZichtbaar = 0;
        }




//        if (in_array('automatischeIncasso', $options['enabled_filters'])) {
//            $builder->add('automatischeIncasso', ChoiceType::class, [
//                'required' => false,
//                'choices' => [
//                    'Ja' => 1,
//                    'Nee' => 0,
//                ],
//            ]);
//        }

//        if (in_array('inschrijvingWoningnet', $options['enabled_filters'])) {
//            $builder->add('inschrijvingWoningnet', ChoiceType::class, [
//                'required' => false,
//                'choices' => [
//                    'Ja' => 1,
//                    'Nee' => 0,
//                ],
//            ]);
//        }
//
//        if (in_array('waPolis', $options['enabled_filters'])) {
//            $builder->add('waPolis', ChoiceType::class, [
//                'required' => false,
//                'choices' => [
//                    'Ja' => 1,
//                    'Nee' => 0,
//                ],
//            ]);
//        }
//
//        if (in_array('wpi', $options['enabled_filters'])) {
//            $builder->add('wpi', CheckboxType::class, [
//                'required' => false,
//            ]);
//        }
        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', \TwBundle\Form\MedewerkerType::class, [
                'required' => false,
                'preset'=>false

            ]);
        }
        if (in_array('aanmelddatum', $options['enabled_filters'])) {
            $builder->add('aanmelddatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

//        if (in_array('afsluitdatum', $options['enabled_filters'])) {
//            $builder->add('afsluitdatum', AppDateRangeType::class, [
//                'required' => false,
//            ]);
//        }

        if (in_array('gekoppeld', $options['enabled_filters'])) {
            $builder->add('gekoppeld', ChoiceType::class, [
                'label' => 'Gekoppeld?',
                'choices'=>[
                    'Gekoppeld'=>true,
                    'Niet gekoppeld'=>false,
                    ],
                'required' => false,
                'data' => false,
            ]);
        }

        if (in_array('actief', $options['enabled_filters'])) {
            $builder->add('actief', CheckboxType::class, [
                'label' => 'Alleen actieve dossiers',
                'required' => false,
                'data' => false,
            ]);
        }
        if (in_array('project', $options['enabled_filters'])) {
            $builder->add('project', ProjectSelectFilterType::class, [
                'label' => 'Project',
                'required' => false,
                'data' => false,
            ]);
        }
        if (in_array('bindingRegio', $options['enabled_filters'])) {
            $builder->add('bindingRegio', EntityType::class, [
                'label' => 'Binding',
                'class'=>Regio::class,
                'required' => false,
            ]);
        }

        if (in_array('intakeStatus', $options['enabled_filters'])) {
            $builder->add('intakeStatus', EntityType::class, [
                'required' => false,
                'class'=>IntakeStatus::class,
            ]);
        }

        //extra filtervelden
        $builder
            ->add('dagbesteding',EntityType::class,[
                'required'=>false,
                'label'=>'Dagbesteding',
                'class'=>Dagbesteding::class,
            ])
            ->add('ritme',EntityType::class,[
                'required'=>false,
                'class'=>Ritme::class,
            ])
            ->add('huisdieren',EntityType::class,[
                'required'=>false,
                'class'=>Huisdieren::class,
            ])
            ->add('roken',EntityType::class,[
                'required'=>false,
                'class'=>Roken::class,
            ])
            ->add('softdrugs',EntityType::class,[
                'required'=>false,
                'class'=>Softdrugs::class,
            ])
            ->add('traplopen',EntityType::class,[
                'required'=>false,
                'class'=>Traplopen::class,
            ])
        ;


        $builder
            ->add('filter', SubmitType::class, ['label' => 'Filteren'])
//            ->add('reset', ResetType::class, ['label' => 'Leegmaken'])
            ->add('download', SubmitType::class, ['label' => 'Downloaden'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FilterType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => KlantFilter::class,
            'data' => new KlantFilter(),
            'enabled_filters' => [
                'appKlant' => ['id', 'naam', 'geslacht','geboortedatum'],
//                'automatischeIncasso',
//                'inschrijvingWoningnet',
//                'waPolis',
                'project',
                'aanmelddatum',
                'gekoppeld',
                'intakeStatus',
                'actief',
                'bindingRegio',
//                'wpi',
//                'medewerker',
//                'ambulantOndersteuner',
            ],
        ]);
    }
}
