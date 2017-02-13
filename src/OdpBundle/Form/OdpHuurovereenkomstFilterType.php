<?php

namespace OdpBundle\Form;

use AppBundle\Form\FilterType;
use AppBundle\Form\AppDateRangeType;
use Symfony\Component\Form\AbstractType;
use OdpBundle\Filter\OdpHuurovereenkomstFilter;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\KlantFilterType;

class OdpHuurovereenkomstFilterType extends AbstractType
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

        if (array_key_exists('odpHuurderKlant', $options['enabled_filters'])) {
            $builder->add('odpHuurderKlant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['odpHuurderKlant'],
            ]);
        }

        if (array_key_exists('odpVerhuurderKlant', $options['enabled_filters'])) {
            $builder->add('odpVerhuurderKlant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['odpVerhuurderKlant'],
            ]);
        }

        if (in_array('startdatum', $options['enabled_filters'])) {
            $builder->add('startdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('einddatum', $options['enabled_filters'])) {
            $builder->add('einddatum', AppDateRangeType::class, [
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
            'data_class' => OdpHuurovereenkomstFilter::class,
        ]);
    }
}
