<?php

namespace IzBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType;
use AppBundle\Form\VrijwilligerFilterType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Project;
use IzBundle\Filter\KoppelingFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KoppelingFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('koppelingStartdatum', $options['enabled_filters'])) {
            $builder->add('koppelingStartdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('koppelingEinddatum', $options['enabled_filters'])) {
            $builder->add('koppelingEinddatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('lopendeKoppelingen', $options['enabled_filters'])) {
            $builder->add('lopendeKoppelingen', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen lopende koppelingen',
            ]);
        }

        if (key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (key_exists('vrijwilliger', $options['enabled_filters'])) {
            $builder->add('vrijwilliger', VrijwilligerFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['vrijwilliger'],
            ]);
        }

        if (in_array('project', $options['enabled_filters'])) {
            $builder->add('project', EntityType::class, [
                'required' => false,
                'class' => Project::class,
                'label' => 'Project',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('project')
                        ->where('project.einddatum IS NULL OR project.einddatum >= :now')
                        ->orderBy('project.naam', 'ASC')
                        ->setParameter('now', new \DateTime());
                },
            ]);
        }

        if (in_array('hulpvraagMedewerker', $options['enabled_filters'])) {
            $builder->add('hulpvraagMedewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'label' => 'Medewerker hulpvraag',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(Hulpvraag::class, 'hulpvraag', 'WITH', 'hulpvraag.medewerker = medewerker')
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
                'label' => 'Medewerker hulpaanbod',
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

        if (in_array('filter', $options['enabled_filters'])) {
            $builder->add('filter', SubmitType::class, ['label' => 'Filteren']);
        }

        if (in_array('download', $options['enabled_filters'])) {
            $builder->add('download', SubmitType::class, ['label' => 'Downloaden']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => KoppelingFilter::class,
            'data' => new KoppelingFilter(),
            'enabled_filters' => [
                'koppelingStartdatum',
                'koppelingEinddatum',
                'lopendeKoppelingen',
                'klant' => ['voornaam', 'achternaam', 'stadsdeel'],
                'vrijwilliger' => ['voornaam', 'achternaam'],
                'project',
                'hulpvraagMedewerker',
                'hulpaanbodMedewerker',
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
