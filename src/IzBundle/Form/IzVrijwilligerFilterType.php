<?php

namespace IzBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\VrijwilligerFilterType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Intake;
use IzBundle\Entity\IzDeelnemer;
use IzBundle\Filter\IzVrijwilligerFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IzVrijwilligerFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('afsluitDatum', $options['enabled_filters'])) {
            $builder->add('afsluitDatum', AppDateRangeType::class, [
                'required' => false,
                'label' => false,
            ]);
        }

        if (in_array('openDossiers', $options['enabled_filters'])) {
            $builder->add('openDossiers', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen open dossiers',
            ]);
        }

        if (key_exists('vrijwilliger', $options['enabled_filters'])) {
            $builder->add('vrijwilliger', VrijwilligerFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['vrijwilliger'],
            ]);
        }

        if (in_array('actief', $options['enabled_filters'])) {
            $builder->add('actief', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Nu actief bij' => IzVrijwilligerFilter::ACTIEF_NU,
                    'Ooit actief bij' => IzVrijwilligerFilter::ACTIEF_OOIT,
                ],
            ]);
        }

        if (in_array('project', $options['enabled_filters'])) {
            $builder->add('project', ProjectSelectFilterType::class, [
            ]);
        }

        if (in_array('hulpvraagsoort', $options['enabled_filters'])) {
            $builder->add('hulpvraagsoort', HulpvraagsoortSelectFilterType::class, [
                'required' => false,
                'expanded' => false,
            ]);
        }

        if (in_array('doelgroep', $options['enabled_filters'])) {
            $builder->add('doelgroep', DoelgroepSelectType::class, [
                'required' => false,
                'expanded' => false,
            ]);
        }

        if (in_array('aanmeldingMedewerker', $options['enabled_filters'])) {
            $builder->add('aanmeldingMedewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'label' => 'Medewerker aanmelding',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(IzDeelnemer::class, 'deelnemer', 'WITH', 'deelnemer.medewerker = medewerker')
                        ->where('medewerker.actief = :true')
                        ->setParameter('true', true)
                        ->orderBy('medewerker.voornaam', 'ASC')
                    ;
                },
            ]);
        }

        if (in_array('intakeMedewerker', $options['enabled_filters'])) {
            $builder->add('intakeMedewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'label' => 'Medewerker intake',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(Intake::class, 'intake', 'WITH', 'intake.medewerker = medewerker')
                        ->where('medewerker.actief = :true')
                        ->setParameter('true', true)
                        ->orderBy('medewerker.voornaam', 'ASC')
                    ;
                },
            ]);
        }

        if (in_array('hulpaanbodMedewerker', $options['enabled_filters'])) {
            $builder->add('hulpaanbodMedewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'label' => 'Medewerker(s) hulpaanbod',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(Hulpaanbod::class, 'hulpaanbod', 'WITH', 'hulpaanbod.medewerker = medewerker')
                        ->where('medewerker.actief = :true')
                        ->setParameter('true', true)
                        ->orderBy('medewerker.voornaam', 'ASC')
                    ;
                },
            ]);
        }

        if (in_array('zonderActiefHulpaanbod', $options['enabled_filters'])) {
            $builder->add('zonderActiefHulpaanbod', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen dossiers zonder actief hulpaanbod',
            ]);
        }

        if (in_array('zonderActieveKoppeling', $options['enabled_filters'])) {
            $builder->add('zonderActieveKoppeling', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen dossiers zonder actieve koppeling',
            ]);
        }

        $builder
            ->add('filter', SubmitType::class, ['label' => 'Filteren'])
            ->add('download', SubmitType::class, ['label' => 'Downloaden'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IzVrijwilligerFilter::class,
            'data' => new IzVrijwilligerFilter(),
            'enabled_filters' => [
                'afsluitDatum',
                'openDossiers',
                'vrijwilliger' => ['id', 'voornaam', 'achternaam', 'geboortedatumRange', 'stadsdeel'],
                'actief',
                'project',
                'hulpvraagsoort',
                'doelgroep',
                'aanmeldingMedewerker',
                'intakeMedewerker',
                'hulpaanbodMedewerker',
                'zonderActiefHulpaanbod',
                'zonderActieveKoppeling',
                'filter',
                'download',
            ],
        ]);
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
