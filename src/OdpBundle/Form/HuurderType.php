<?php

namespace OdpBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\KlantType;
use OdpBundle\Entity\Huurder;
use OdpBundle\Entity\Verslag;
use AppBundle\Form\ZrmType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HuurderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $huurder = $options['data'];

        if ($huurder->getKlant()->getId()) {
            $builder->add('medewerker', MedewerkerType::class);
        } else {
            $builder
                ->add('klant', KlantType::class)
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    $event->getData()->setMedewerker($event->getData()->getKlant()->getMedewerker());
                })
            ;
        }

        $builder
            ->add('ambulantOndersteuner', \AppBundle\Form\MedewerkerType::class,[
                'required'=>false,
                'preset'=>false,

            ])
            ->add('aanmelddatum', AppDateType::class)
            ->add('projecten', ProjectSelectType::class,['multiple'=>true])
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


                if ($huurder->getZrm()) {
                    $builder->add('zrm', ZrmType::class, [
                        'data_class' => get_class($huurder->getZrm()),
                        'request_module' => 'OdpHuurder',
                    ]);
                } else {
                    $builder->add('zrm', ZrmType::class, [
                        'request_module' => 'OdpHuurder',
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
            'data_class' => Huurder::class,
            'request_module'=>'OdpHuurder'
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
