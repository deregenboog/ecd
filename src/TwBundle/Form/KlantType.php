<?php

namespace TwBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\ActiveEntityType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseSelectType;
use AppBundle\Form\BaseType;
use AppBundle\Form\KlantType as AppKlantType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use TwBundle\Entity\Alcohol;
use TwBundle\Entity\Dagbesteding;
use TwBundle\Entity\DuurThuisloos;
use TwBundle\Entity\Huisdieren;
use TwBundle\Entity\Inkomen;
use TwBundle\Entity\InschrijvingWoningnet;
use TwBundle\Entity\IntakeStatus;
use TwBundle\Entity\Klant;
use TwBundle\Entity\MoScreening;
use TwBundle\Entity\Regio;
use TwBundle\Entity\Ritme;
use TwBundle\Entity\Roken;
use TwBundle\Entity\Softdrugs;
use TwBundle\Entity\Traplopen;
use TwBundle\Entity\Verslag;
use AppBundle\Form\ZrmType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Entity\Werk;
use TwBundle\Repository\WerkRepository;

class KlantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $klant = $options['data'];

        /**
         * Waarom zat dit er nou in? Snap ik niet...
         */
        if ($klant->getAppKlant()->getId()) {
//            $builder->add('medewerker', MedewerkerType::class);
        } else {
            $builder
                ->add('appKlant', AppKlantType::class)
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    $event->getData()->setMedewerker($event->getData()->getAppKlant()->getMedewerker());
                })
            ;
        }

        $builder->add('medewerker', MedewerkerType::class,['required'=>false])
                ->add('begeleider')
                ->add('aanmelddatum', AppDateType::class, ['required'=>true])
                ->add('projecten', ProjectSelectType::class,['multiple'=>true,'required'=>true])
                ->add('shortlist', MedewerkerType::class,['required'=>false])
                ->add('intakeStatus',ActiveEntityType::class,['class'=>IntakeStatus::class])
       ;
      $builder
           ->add('moScreening',ActiveEntityType::class,['class'=>MoScreening::class])
            ->add('bindingRegio',ActiveEntityType::class,['class'=>Regio::class])
            ->add('inschrijvingWoningnet')
            ->add('klantmanager')
            ->add('wpi')
            ->add('huurbudget')
            ->add('duurThuisloos',ActiveEntityType::class,['class'=>DuurThuisloos::class])
            ->add('werk',ActiveEntityType::class,['class'=>Werk::class])
            ->add('inkomen',ActiveEntityType::class,['class'=>Inkomen::class])
            ->add('inkomensverklaring',CheckboxType::class,['required'=>false])
            ->add('toelichtingInkomen',null,['required'=>false])
            ->add('dagbesteding')
            ->add('ritme',ActiveEntityType::class,['class'=>Ritme::class])
            ->add('huisdieren')
            ->add('roken',ActiveEntityType::class,['class'=>Roken::class])
            ->add('softdrugs',ActiveEntityType::class,['class'=>Softdrugs::class])
            ->add('alcohol',ActiveEntityType::class,['class'=>Alcohol::class])
            ->add('traplopen',ActiveEntityType::class,['class'=>Traplopen::class])
        ;

        if (!$options['data']->getId()) {
            $builder
                ->add('opmerking', TextareaType::class, [
                    'label' => 'Intakeverslag',
                    'required' => false,
                    'mapped' => false,
                    'attr' => ['rows' => 10],
                ])
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    if ($event->getForm()->get('opmerking')->getData()) {
                        $verslag = new Verslag();
                        $verslag
                            ->setDatum($event->getData()->getAanmelddatum())
                            ->setOpmerking($event->getForm()->get('opmerking')->getData())
                            ->setMedewerker($event->getData()->getMedewerker())
                        ;
                        $event->getData()->addVerslag($verslag);
                    }
                })
            ;
        }


        if ($klant->getZrm()) {
            $builder->add('zrm', ZrmType::class, [
                'data_class' => get_class($klant->getZrm()),
                'request_module' => 'TwKlant',
                'required'=>false,
            ]);
        } else {
            $builder->add('zrm', ZrmType::class, [
                'request_module' => 'TwKlant',
                'required'=>false,
            ]);
        }

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Klant::class,
            'request_module'=>'TwKlant'
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
