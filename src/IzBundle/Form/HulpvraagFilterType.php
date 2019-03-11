<?php

namespace IzBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateType;
use AppBundle\Form\KlantFilterType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Project;
use IzBundle\Filter\HulpvraagFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\MedewerkerType;

class HulpvraagFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('matching', $options['enabled_filters'])) {
            $builder->add('matching', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen matchende kandidaten tonen',
            ]);
        }

        if (in_array('startdatum', $options['enabled_filters'])) {
            $builder->add('startdatum', AppDateType::class, [
                'required' => false,
            ]);
        }

        if (key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
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
            $builder->add('medewerker', MedewerkerType::class, [
                'required' => false,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(Hulpvraag::class, 'hulpvraag', 'WITH', 'hulpvraag.medewerker = medewerker')
                        ->where('medewerker.actief = true')
                        ->orderBy('medewerker.voornaam', 'ASC');
                },
                'preset' => $options['preset_medewerker'],
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
            'data_class' => HulpvraagFilter::class,
            'data' => new HulpvraagFilter(),
            'enabled_filters' => [
                'startdatum',
                'klant' => ['id', 'voornaam', 'achternaam', 'geboortedatumRange', 'stadsdeel'],
                'project',
                'medewerker',
                'filter',
                'download',
            ],
            'preset_medewerker' => false,
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
