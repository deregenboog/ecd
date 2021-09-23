<?php

namespace TwBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\KlantType as AppKlantType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use TwBundle\Entity\Klant;
use TwBundle\Entity\Verslag;
use AppBundle\Form\ZrmType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

         $builder
             ->add('begeleider')
            ->add('aanmelddatum', AppDateType::class, ['required'=>true])
            ->add('projecten', ProjectSelectType::class,['multiple'=>true,'required'=>true])


            ->add('intake',CheckboxType::class,['mapped'=>false,'required'=>false,'value'=>false])
       ;
      $builder
           ->add('moScreening')
            ->add('bindingRegio')
            ->add('rekeningnummer', null, ['required' => false])
            ->add('automatischeIncasso', null, ['required' => false])
            ->add('inschrijvingWoningnet', null, [
                'required' => false,
                'label' => 'Inschrijving Woningnet',
            ])
            ->add('waPolis', null, [
                'required' => false,
                'label' => 'WA-polis',
            ])
            ->add('klantmanager')
            ->add('wpi')
            ->add('huurbudget')
            ->add('duurThuisloos')
            ->add('werk')
            ->add('inkomen')
            ->add('dagbesteding')
            ->add('ritme')
            ->add('huisdieren')
            ->add('roken')
            ->add('softdrugs')
            ->add('traplopen')
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
                'request_module' => 'TwHuurder',
                'required'=>false,
            ]);
        } else {
            $builder->add('zrm', ZrmType::class, [
                'request_module' => 'TwHuurder',
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
            'request_module'=>'TwHuurder'
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
