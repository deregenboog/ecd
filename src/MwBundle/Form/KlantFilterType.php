<?php

namespace MwBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use AppBundle\Form\FilterType;
use MwBundle\Filter\KlantFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use InloopBundle\Entity\Locatie;
use AppBundle\Form\AppDateRangeType;
use InloopBundle\Form\GebruikersruimteSelectType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

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

        if (in_array('gebruikersruimte', $options['enabled_filters'])) {
            $builder->add('gebruikersruimte', GebruikersruimteSelectType::class, [
                'required' => false,
            ]);
        }

        if (in_array('laatsteIntakeLocatie', $options['enabled_filters'])) {
            $builder->add('laatsteIntakeLocatie', EntityType::class, [
                'class' => Locatie::class,
                'required' => false,
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
                'laatsteIntakeLocatie',
                'laatsteVerslagDatum',
                'alleenMetVerslag',
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
