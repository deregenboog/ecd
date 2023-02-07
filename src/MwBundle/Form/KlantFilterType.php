<?php

namespace MwBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use Doctrine\ORM\EntityRepository;
use InloopBundle\Form\LocatieSelectType;
use MwBundle\Entity\Verslag;
use MwBundle\Filter\KlantFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', AppKlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }
//        if (array_key_exists('verslag', $options['enabled_filters'])) {
//            $builder->add('verslag', EntityType::class, [
//                'required' => false,
//                'class'=>Medewerker::class,
//
//            ]);
//        }
        if (in_array('maatschappelijkWerker', $options['enabled_filters'])) {
            $builder->add('maatschappelijkWerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'query_builder' => function (EntityRepository $repository) {
                    $builder = $repository->createQueryBuilder('medewerker')
                        ->select('medewerker')
                        ->innerJoin(Verslag::class, 'verslag', 'WITH', 'verslag.medewerker = medewerker')
                        ->where('verslag.type = 1')
                        ->orderBy('medewerker.voornaam')
                        ->groupBy('verslag.medewerker')
                        ;
                    $sql = $builder->getQuery()->getSQL();

                    return $builder;
                },
            ]);
        }

        if (in_array('laatsteIntakeDatum', $options['enabled_filters'])) {
            $builder->add('laatsteIntakeDatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('laatsteVerslagDatum', $options['enabled_filters'])) {
            $builder->add('laatsteVerslagDatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('huidigeMwStatus', $options['enabled_filters'])) {
            $builder->add('huidigeMwStatus', ChoiceType::class, [
                'choices' => [
                    'Aangemaakt' => 'aangemaakt',
                    'Aangemeld' => 'aangemeld',
                    'Intake Algemeen afgerond' => 'intake_algemeen_afgerond',
                    'Intake huisvesting afgerond' => 'intake_huisvesting_afgerond',
                    'Intake inkomen afgerond' => 'intake_inkomen_afgerond',
                    'Intake welzijn afgerond' => 'intake_welzijn_afgerond',
                    'Intake administratie afgerond' => 'intake_administratie_afgerond',
                    'Intake verwachting afgerond' => 'intake_verwachting_afgerond',
                    'Intake_gezin afgerond' => 'intake_gezin_afgerond',
                    'In traject' => 'in_traject',
                    'Afgesloten' => 'afgesloten',

                ],
                'required' => false,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => KlantFilter::class,
            'data' => new KlantFilter(),
            'enabled_filters' => [
                'klant' => ['id', 'naam', 'geboortedatumRange', 'geslacht'],
                'gebruikersruimte',
                'maatschappelijkWerker',
                'laatsteVerslagLocatie',
                'laatsteVerslagDatum',
                'huidigeMwStatus',
//                'verslag' => ['medewerker'],
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
