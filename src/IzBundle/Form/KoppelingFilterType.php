<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\VrijwilligerFilterType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulp;
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

        if (in_array('langeKoppelingen', $options['enabled_filters'])) {
            $builder->add('langeKoppelingen', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen koppelingen > 6 maanden',
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

        if (in_array('hulpvraagMedewerker', $options['enabled_filters'])) {
            $builder->add('hulpvraagMedewerker', MedewerkerType::class, [
                'required' => false,
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
                'preset' => $options['preset_medewerker'],
            ]);
        }

        if (in_array('hulpaanbodMedewerker', $options['enabled_filters'])) {
            $builder->add('hulpaanbodMedewerker', MedewerkerType::class, [
                'required' => false,
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
                'preset' => $options['preset_medewerker'],
            ]);
        }

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', MedewerkerType::class, [
                'required' => false,
                'query_builder' => function (EntityRepository $repo) {
                    $builder = $repo->createQueryBuilder('medewerker')
                        ->select('medewerker')
                        ->innerJoin(Hulp::class, 'hulp', 'WITH', 'hulp.medewerker = medewerker')
                        ->where('medewerker.actief = true')
                        ->orderBy('medewerker.voornaam', 'ASC')
                        ->groupBy("medewerker.id")
                    ;
//                    $sql = SqlExtractor::getFullSQL($builder->getQuery());
                    return $builder;
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
            'data_class' => KoppelingFilter::class,
            'data' => new KoppelingFilter(),
            'enabled_filters' => [
                'koppelingStartdatum',
                'koppelingEinddatum',
                'lopendeKoppelingen',
                'langeKoppelingen',
                'klant' => ['voornaam', 'achternaam', 'stadsdeel'],
                'vrijwilliger' => ['voornaam', 'achternaam'],
                'project',
                'hulpvraagsoort',
                'doelgroep',
                'hulpvraagMedewerker',
//                'hulpaanbodMedewerker',
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
        return FilterType::class;
    }
}
