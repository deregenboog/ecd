<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\FilterType;
use AppBundle\Entity\Medewerker;
use IzBundle\Entity\Project;
use IzBundle\Filter\IzVrijwilligerFilter;
use IzBundle\Entity\IzHulpaanbod;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Form\VrijwilligerFilterType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use AppBundle\Form\AppDateRangeType;
use IzBundle\Entity\IzIntake;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class IzVrijwilligerFilterType extends AbstractType
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
            $builder->add('project', EntityType::class, [
                'required' => false,
                'label' => 'Project',
                'class' => Project::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('project')
                        ->where('project.einddatum IS NULL OR project.einddatum >= :now')
                        ->orderBy('project.naam', 'ASC')
                        ->setParameter('now', new \DateTime());
                },
            ]);
        }

        if (in_array('izIntakeMedewerker', $options['enabled_filters'])) {
            $builder->add('izIntakeMedewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'label' => 'Medewerker intake',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(IzIntake::class, 'izIntake', 'WITH', 'izIntake.medewerker = medewerker')
                        ->where('medewerker.actief = :true')
                        ->setParameter('true', true)
                        ->orderBy('medewerker.voornaam', 'ASC')
                    ;
                },
            ]);
        }

        if (in_array('izHulpaanbodMedewerker', $options['enabled_filters'])) {
            $builder->add('izHulpaanbodMedewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'label' => 'Medewerker(s) hulpaanbod',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(IzHulpaanbod::class, 'izHulpaanbod', 'WITH', 'izHulpaanbod.medewerker = medewerker')
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

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IzVrijwilligerFilter::class,
            'enabled_filters' => [
                'afsluitDatum',
                'openDossiers',
                'vrijwilliger' => ['id', 'voornaam', 'achternaam', 'geboortedatumRange', 'stadsdeel'],
                'actief',
                'project',
                'izIntakeMedewerker',
                'izHulpaanbodMedewerker',
                'zonderActiefHulpaanbod',
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
