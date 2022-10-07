<?php

namespace OekraineBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use OekraineBundle\Filter\RegistratieFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistratieFilterType extends AbstractType
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

        if (in_array('locatie', $options['enabled_filters'])) {
            $builder->add('locatie', LocatieSelectType::class, [
                'required' => false,
            ]);
        }
        if (in_array('woonlocatie', $options['enabled_filters'])) {
            $builder->add('woonlocatie', LocatieSelectType::class, [
                'required' => false,
            ]);
        }

        if (in_array('binnen', $options['enabled_filters'])) {
            $builder->add('binnen', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('buiten', $options['enabled_filters'])) {
            $builder->add('buiten', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

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
            'data_class' => RegistratieFilter::class,
            'enabled_filters' => [
                'klant' => ['voornaam', 'achternaam'],
//                'locatie',
                'binnen',
                'woonlocatie',
                'filter',
            ],
        ]);
    }
}
