<?php

namespace IzBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Intake;
use IzBundle\Entity\IzDeelnemer;
use IzBundle\Entity\Project;
use IzBundle\Form\DoelgroepSelectType;
use IzBundle\Form\HulpvraagsoortSelectType;
use IzBundle\Filter\IzKlantFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IzKlantFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
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

        if (key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (in_array('actief', $options['enabled_filters'])) {
            $builder->add('actief', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Nu actief bij' => IzKlantFilter::ACTIEF_NU,
                    'Ooit actief bij' => IzKlantFilter::ACTIEF_OOIT,
                ],
            ]);
        }

        if (in_array('project', $options['enabled_filters'])) {
            $builder->add('project', ProjectSelectFilterType::class,[
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

        if (in_array('hulpvraagMedewerker', $options['enabled_filters'])) {
            $builder->add('hulpvraagMedewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'label' => 'Medewerker(s) hulpvraag',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(Hulpvraag::class, 'hulpvraag', 'WITH', 'hulpvraag.medewerker = medewerker')
                        ->where('medewerker.actief = :true')
                        ->setParameter('true', true)
                        ->orderBy('medewerker.voornaam', 'ASC');
                },
            ]);
        }

        if (in_array('zonderActieveHulpvraag', $options['enabled_filters'])) {
            $builder->add('zonderActieveHulpvraag', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen dossiers zonder actieve hulpvraag',
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

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IzKlantFilter::class,
            'data' => new IzKlantFilter(),
            'enabled_filters' => [
                'afsluitDatum',
                'openDossiers',
                'klant' => ['id', 'voornaam', 'achternaam', 'geboortedatumRange', 'stadsdeel'],
                'actief',
                'project',
                'hulpvraagsoort',
                'doelgroep',
                'aanmeldingMedewerker',
                'intakeMedewerker',
                'hulpvraagMedewerker',
                'zonderActieveHulpvraag',
                'zonderActieveKoppeling',
                'filter',
                'download',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FilterType::class;
    }
}
