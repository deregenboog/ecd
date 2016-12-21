<?php

namespace GaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class GaRapportageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startdatum', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'data' => new \DateTime('first day of January this year'),
                'attr' => ['placeholder' => 'dd-mm-jjjj'],
            ])
            ->add('einddatum', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'data' => (new \DateTime('today')),
                'attr' => ['placeholder' => 'dd-mm-jjjj'],
            ])
            ->add('rapport', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selecteer een rapport',
                'choices' => [
                    'Buurtmaatjes' => [
                        'Buurtmaatjes deelnemers totaal' => 'buurtmaatjes_deelnemers_totaal',
                        'Buurtmaatjes deelnemers per stadsdeel' => 'buurtmaatjes_deelnemers_per_stadsdeel',
                        'Buurtmaatjes vrijwilligers totaal' => 'buurtmaatjes_vrijwilligers_totaal',
                    ],
                    'ErOpUit' => [
                        'ErOpUit deelnemers totaal' => 'eropuit_deelnemers_totaal',
                        'ErOpUit deelnemers per stadsdeel' => 'eropuit_deelnemers_per_stadsdeel',
                        'ErOpUit vrijwilligers totaal' => 'eropuit_vrijwilligers_totaal',
                    ],
                    'Open Huizen' => [
                        'Open Huis deelnemers totaal' => 'openhuis_deelnemers_totaal',
                        'Open Huis deelnemers per stadsdeel' => 'openhuis_deelnemers_per_stadsdeel',
                        'Open Huis vrijwilligers totaal' => 'openhuis_vrijwilligers_totaal',
                    ],
                    'Organisatie' => [
                        'Ondersteunende vrijwilligers totaal' => 'organisatie_vrijwilligers_totaal',
                    ],
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
        ]);
    }
}
