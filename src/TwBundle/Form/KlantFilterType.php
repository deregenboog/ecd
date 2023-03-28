<?php

namespace TwBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use AppBundle\Form\StadsdeelSelectType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Exception\OutOfBoundsException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Entity\Alcohol;
use TwBundle\Entity\Dagbesteding;
use TwBundle\Entity\Huisdieren;
use TwBundle\Entity\IntakeStatus;
use TwBundle\Entity\Regio;
use TwBundle\Entity\Ritme;
use TwBundle\Entity\Roken;
use TwBundle\Entity\Softdrugs;
use TwBundle\Entity\Traplopen;
use TwBundle\Filter\KlantFilter;

class KlantFilterType extends AbstractType
{
    /** @var EntityManager */
    private $entityManager = null;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
                'preset' => false,
            ]);
        }
        if (in_array('shortlist', $options['enabled_filters'])) {
            $builder->add('shortlist', \TwBundle\Form\MedewerkerType::class, [
                'required' => false,
                'preset' => false,
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
                'choices' => [
                    'Ja en nee' => null,
                    'Gekoppeld' => true,
                    'Niet gekoppeld' => false,
                ],
                'required' => false,
//                'data' => false,
            ]);
        }

        if (in_array('status', $options['enabled_filters'])) {
            $builder->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Actief' => KlantFilter::STATUS_ACTIVE,
                    'Niet actief' => KlantFilter::STATUS_NON_ACTIVE,
                ],
            ]);
        }

        if (in_array('project', $options['enabled_filters'])) {
            $builder->add('project', ProjectSelectFilterType::class, [
                'label' => 'Project',
                'required' => false,
                'multiple' => true,
//                'data' => false,
            ]);
        }

        if (in_array('bindingRegio', $options['enabled_filters'])) {
            $builder->add('bindingRegio', ChoiceType::class, [
                'label' => 'Binding',
                'choices' => $this->loadChoices(Regio::class),
                'multiple' => true,
                'required' => false,
            ]);
        }

        if (in_array('intakeStatus', $options['enabled_filters'])) {
            $builder->add('intakeStatus', ChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'choices' => $this->loadChoices(IntakeStatus::class),
            ]);
        }

        //extra filtervelden
        $builder
            ->add('dagbesteding', ChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'label' => 'Dagbesteding',
                'choices' => $this->loadChoices(Dagbesteding::class),
            ])
            ->add('ritme', ChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'choices' => $this->loadChoices(Ritme::class),
            ])
            ->add('heeftHuisgenoot', ChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'choices' => [
                    'Onbekend'=>null,
                    'Ja'=>true,
                    'Nee'=>false
                ],
            ])
            ->add('huisdieren', ChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'choices' => $this->loadChoices(Huisdieren::class),
            ])
            ->add('roken', ChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'choices' => $this->loadChoices(Roken::class),
            ])
            ->add('softdrugs', ChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'choices' => $this->loadChoices(Softdrugs::class),
            ])
            ->add('alcohol', ChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'choices' => $this->loadChoices(Alcohol::class),
            ])
            ->add('inkomensverklaring', ChoiceType::class, [
                 'label' => 'Inkomensverklaring',
                 'choices' => [
                     'Onbekend' => null,
                     'Ja' => true,
                     'Nee' => false,
                 ],
                 'multiple' => true,
                 'required' => false,
//                 'data' => false,
             ])
            ->add('traplopen', ChoiceType::class, [
                'required' => false,
                'multiple' => true,

                'choices' => $this->loadChoices(Traplopen::class),
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
                'appKlant' => ['id', 'naam', 'geslacht', 'geboortedatumRange'],
//                'automatischeIncasso',
//                'inschrijvingWoningnet',
//                'waPolis',
                'project',
                'aanmelddatum',
                'gekoppeld',
                'intakeStatus',
                'status',
                'bindingRegio',
                'shortlist',
//                'wpi',
                'medewerker',
                'heeftHuisgenoot',
            ],
            'validation_groups' => false,
//                function(FormInterface $form) {
//                    foreach($form->getData() as$k=> $v)
//                    {
//                        try {
//                            $child = $form->get($k);
//                            if(null!==$child->getTransformationFailure())
//                            {
//
//                            }
//
//                        }
//                        catch(OutOfBoundsException $e){
//
//                        }
//                    }
//            }
        ]);
    }

    /**
     * @param array $options
     *
     * Make empty options for multi inputs
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
//        foreach($view->children as $k=>$formElm)
//        {
//            if(($c = $form->get($k)->getConfig()) && $c->getOption('multiple')===true )
//            {
//                $newChoice = new ChoiceView(array(), '100', 'Onbekend'); // <- new option
//                array_unshift($formElm->vars['choices'], $newChoice);//<- adding the new option to the start
//            }
//
//        }
    }

    private function loadChoices($class)
    {
        $r = $this->entityManager->getRepository($class);
        $c = $r->findBy(['actief' => true]);

        $choices = ['Onbekend' => 0];
        foreach ($c as $e) {
            $choices[$e->getNaam()] = $e->getId();
        }

        return $choices;
    }
}
