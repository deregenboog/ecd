<?php

namespace DagbestedingBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\BaseSelectType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType;
use DagbestedingBundle\Entity\Dagdeel;
use DagbestedingBundle\Entity\Project;
use DagbestedingBundle\Filter\DagdeelFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DagdeelFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

//         if (array_key_exists('traject', $options['enabled_filters'])) {
//             $builder->add('traject', KlantFilterType::class, [
//                 'enabled_filters' => $options['enabled_filters']['klant'],
//             ]);
//         }

        if (in_array('project', $options['enabled_filters'])) {
            $builder->add('project', BaseSelectType::class, [
                'class' => Project::class,
                'required' => false,
                'placeholder' => '',
            ]);
        }

        if (in_array('datum', $options['enabled_filters'])) {
            $builder->add('datum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('dagdeel', $options['enabled_filters'])) {
            $builder->add('dagdeel', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    Dagdeel::DAGDEEL_OCHTEND => Dagdeel::DAGDEEL_OCHTEND,
                    Dagdeel::DAGDEEL_MIDDAG => Dagdeel::DAGDEEL_MIDDAG,
                    Dagdeel::DAGDEEL_AVOND => Dagdeel::DAGDEEL_AVOND,
                ],
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
    public function getParent()
    {
        return FilterType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DagdeelFilter::class,
            'enabled_filters' => [
                'klant' => ['id', 'naam'],
                'project',
                'datum',
                'dagdeel',
            ],
        ]);
    }
}
