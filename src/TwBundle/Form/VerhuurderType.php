<?php

namespace TwBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\KlantType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use TwBundle\Entity\Verhuurder;
use TwBundle\Entity\Verslag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerhuurderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $verhuurder = $options['data'];
        $appKlant = $verhuurder->getAppKlant();

        if ($options['data']->getAppKlant() && $options['data']->getAppKlant()->getId()) {
            $builder->add('medewerker', MedewerkerType::class);
        } else {
            $builder
                ->add('appKlant', KlantType::class)
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    $event->getData()->setMedewerker($event->getData()->getKlant()->getMedewerker());
                })
            ;
        }

        $builder
            ->add('begeleider',  null,[
                'required'=>false,
//                'preset'=>false,

            ])
            ->add('project', ProjectSelectType::class)
            ->add('aanmelddatum', AppDateType::class)
            ->add('rekeningnummer', null, ['required' => false])
            ->add('pandeigenaar')
            ->add('pandeigenaarToelichting', null, ['label' => 'Pandeigenaar anders/toelichting'])
            ->add('klantmanager')
            ->add('wpi')
            ->add('ksgw')
            ->add('inkomen')
            ->add('aanvullingInkomen')
            ->add('huurtoeslag', ChoiceType::class,['choices' => [
                    'Ja' => '1',
                    'Nee' => '0',
                    'Onbekend' => null,
                ],
                'required'=>false,
            ])
            ->add('kwijtschelding')
            ->add('samenvatting', AppTextareaType::class)
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

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Verhuurder::class,
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
