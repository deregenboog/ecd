<?php

namespace IzBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateType;
use AppBundle\Form\VrijwilligerFilterType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Project;
use IzBundle\Filter\HulpaanbodFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class HulpaanbodFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('matching', $options['enabled_filters'])) {
            $builder->add('matching', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen matchende kandidaten tonen'
            ]);
        }

        if (in_array('startdatum', $options['enabled_filters'])) {
            $builder->add('startdatum', AppDateType::class, [
                'required' => false,
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
                        ->setParameter('now', new \DateTime())
                        ;
                },
            ]);
        }

        if (in_array('hulpvraagsoort', $options['enabled_filters'])) {
            $builder->add('hulpvraagsoort', HulpvraagsoortSelectType::class, [
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

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
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
            'data_class' => HulpaanbodFilter::class,
            'enabled_filters' => [
                'startdatum',
                'vrijwilliger' => ['id', 'voornaam', 'achternaam', 'geboortedatumRange', 'stadsdeel'],
                'project',
                'medewerker',
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
        return KoppelingFilterType::class;
    }
}
