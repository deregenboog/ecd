<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class IzRapportageType extends AbstractType
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
                    'Koppelingen' => [
                        'Koppelingen totaal' => 'koppelingen_totaal',
//                         'Koppelingen per coördinator' => 'koppelingen_per_coordinator',
                        'Koppelingen per project' => 'koppelingen_per_project',
                        'Koppelingen per stadsdeel' => 'koppelingen_per_stadsdeel',
//                         'Koppelingen per postcodegebied' => 'koppelingen_per_postcodegebied',
//                         'Koppelingen per project en stadsdeel' => 'koppelingen_per_project_stadsdeel',
//                         'Koppelingen per project en postcodegebied' => 'koppelingen_per_project_postcodegebied',
                    ],
                    'Vrijwilligers' => array(
//                         'Aanmeldingen vrijwilligers' => 'vrijwilligers_aanmeldingen',
//                         'Aanmeldingen vrijwilligers per coördinator' => 'vrijwilligers_aanmeldingen_coordinator',
                        'Vrijwilligers totaal' => 'vrijwilligers_totaal',
//                         'Vrijwilligers per coördinator' => 'vrijwilligers_per_coordinator',
                        'Vrijwilligers per project' => 'vrijwilligers_per_project',
                        'Vrijwilligers per stadsdeel' => 'vrijwilligers_per_stadsdeel',
//                         'Vrijwilligers per postcodegebied' => 'vrijwilligers_per_postcodegebied',
//                         'Vrijwilligers per project en stadsdeel' => 'vrijwilligers_per_project_stadsdeel',
//                         'Vrijwilligers per project en postcodegebied' => 'vrijwilligers_per_project_postcodegebied',
                    ),
                    'Klanten' => array(
//                         'Aanmeldingen klanten' => 'klanten_aanmeldingen',
//                         'Aanmeldingen klanten per coördinator' => 'klanten_aanmeldingen_coordinator',
                        'Klanten totaal' => 'klanten_totaal',
//                         'Klanten per coördinator' => 'klanten_per_coordinator',
                        'Klanten per project' => 'klanten_per_project',
                        'Klanten per stadsdeel' => 'klanten_per_stadsdeel',
//                         'Klanten per postcodegebied' => 'klanten_per_postcodegebied',
//                         'Klanten per project en stadsdeel' => 'klanten_per_project_stadsdeel',
//                         'Klanten per project en postcodegebied' => 'klanten_per_project_postcodegebied',
                    ),
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
