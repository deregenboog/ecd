<?php

namespace MwBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\JaNeeType;
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', AppKlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (in_array('maatschappelijkWerker', $options['enabled_filters'])) {
            $builder->add('maatschappelijkWerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'query_builder' => function (EntityRepository $repository) {
                    $builder = $repository->createQueryBuilder('medewerker')
                        ->select('medewerker')
                        ->innerJoin(Verslag::class, 'verslag', 'WITH', 'verslag.medewerker = medewerker')
                        ->where('verslag.type = 1')
                        ->groupBy('verslag.medewerker')
                        ->orderBy('medewerker.voornaam')
                    ;

                    return $builder;
                },
            ]);
        }
        if (in_array('gebruikersruimte', $options['enabled_filters'])) {
            $builder->add('gebruikersruimte', LocatieSelectType::class, [
                'required' => false,
                'gebruikersruimte' => true,
            ]);
        }

        if (in_array('laatsteVerslagLocatie', $options['enabled_filters'])) {
            $builder->add('laatsteVerslagLocatie', LocatieSelectType::class, [
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
                'choices' => ['Aangemeld' => 'Aanmelding', 'Afgesloten' => 'Afsluiting'],
                'required' => false,
                'data' => 'Aanmelding',
            ]);
        }

        if (in_array('isGezin', $options['enabled_filters'])) {
            $builder->add('isGezin', JaNeeType::class, [
                'placeholder' => 'Ja/Nee',
                'expanded' => false,
                'required' => false,
            ]);
        }

        if (in_array('project', $options['enabled_filters'])) {
            $builder->add('project', ProjectSelectType::class, [
                'required' => false,
            ]);
        }
    }

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
                'isGezin',
                'project',
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
