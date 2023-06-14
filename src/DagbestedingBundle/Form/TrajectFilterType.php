<?php

namespace DagbestedingBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\BaseSelectType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType;
use DagbestedingBundle\Entity\Locatie;
use DagbestedingBundle\Entity\Project;
use DagbestedingBundle\Entity\Trajectcoach;
use DagbestedingBundle\Entity\Trajectsoort;
use DagbestedingBundle\Filter\TrajectFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrajectFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'required' => false,
            ]);
        }

        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (in_array('soort', $options['enabled_filters'])) {
            $builder->add('soort', EntityType::class, [
                'class' => Trajectsoort::class,
                'required' => false,
            ]);
        }

        if (array_key_exists('resultaatgebied', $options['enabled_filters'])) {
            $builder->add('resultaatgebied', ResultaatgebiedFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['resultaatgebied'],
            ]);
        }

        if (in_array('trajectcoach', $options['enabled_filters'])) {
            $builder->add('trajectcoach', BaseSelectType::class, [
                'class' => Trajectcoach::class,
                'required' => false,
            ]);
        }

        if (in_array('project', $options['enabled_filters'])) {
            $builder->add('project', BaseSelectType::class, [
                'class' => Project::class,
                'required' => false,
            ]);
        }

        if (in_array('locatie', $options['enabled_filters'])) {
            $builder->add('locatie', BaseSelectType::class, [
                'class' => Locatie::class,
                'required' => false,
            ]);
        }

        if (in_array('startdatum', $options['enabled_filters'])) {
            $builder->add('startdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('evaluatiedatum', $options['enabled_filters'])) {
            $builder->add('evaluatiedatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('einddatum', $options['enabled_filters'])) {
            $builder->add('einddatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('afsluitdatum', $options['enabled_filters'])) {
            $builder->add('afsluitdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('actief', $options['enabled_filters'])) {
            $builder->add('actief', CheckboxType::class, [
                'label' => 'Alleen actieve trajecten',
                'required' => false,
                'data' => true,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return FilterType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrajectFilter::class,
            'data'=>new TrajectFilter(),
            'enabled_filters' => [
                'klant' => ['naam'],
                'soort',
                'resultaatgebied' => ['soort'],
                'trajectcoach',
                'project',
                'startdatum',
                'evaluatiedatum',
                'afsluitdatum',
                'actief',
                'filter',
                'download',
            ],
        ]);
    }
}
