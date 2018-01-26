<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use InloopBundle\Filter\SchorsingFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchorsingFilterType extends AbstractType
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

        if (in_array('datumVan', $options['enabled_filters'])) {
            $builder->add('datumVan', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('datumTot', $options['enabled_filters'])) {
            $builder->add('datumTot', AppDateRangeType::class, [
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
            'data_class' => SchorsingFilter::class,
            'enabled_filters' => [
                'klant' => ['id', 'naam', 'geslacht'],
                'locatie',
                'datumVan',
                'datumTot',
                'filter',
                'download',
            ],
        ]);
    }
}
