<?php

namespace MwBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\MedewerkerFilterType as AppMedewerkerFilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use Doctrine\ORM\EntityRepository;
use GaBundle\Form\SelectieType;
use InloopBundle\Entity\Locatie;
use InloopBundle\Form\LocatieSelectType;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\MwDossierStatus;
use MwBundle\Entity\Verslag;
use MwBundle\Filter\KlantFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

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
                'class'=>Medewerker::class,
                'query_builder' => function (EntityRepository $repository) {
                    $builder = $repository->createQueryBuilder('medewerker')
                        ->select("medewerker")
                        ->innerJoin(Verslag::class, 'verslag', 'WITH', 'verslag.medewerker = medewerker')
                        ->where('verslag.type = 1')
                        ->orderBy('medewerker.voornaam')
                        ->groupBy('verslag.medewerker')
                        ;
                    $sql = $builder->getQuery()->getSQL();
                    return $builder;
                    ;
                },
            ]);

        }
        if (in_array('gebruikersruimte', $options['enabled_filters'])) {
            $builder->add('gebruikersruimte', LocatieSelectType::class, [
                'required' => false,
                'gebruikersruimte' => true,
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

        if (in_array('alleenMetVerslag', $options['enabled_filters'])) {
            $builder->add('alleenMetVerslag', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen klanten Maatschappelijk Werk tonen',
            ]);
        }

        if (in_array('huidigeMwStatus', $options['enabled_filters'])) {
            $builder->add('huidigeMwStatus', ChoiceType::class, [

                'choices'=>['Aangemeld'=>'Aanmelding','Afgesloten'=>'Afsluiting'],
                'required' => false,
               'data'=>'Aanmelding'

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
                'laatsteIntakeLocatie',
                'laatsteVerslagDatum',
                'alleenMetVerslag',
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
