<?php

namespace OdpBundle\Form;

use AppBundle\Form\FilterType;
use AppBundle\Form\AppDateRangeType;
use Symfony\Component\Form\AbstractType;
use OdpBundle\Filter\OdpHuurovereenkomstFilter;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

        if (array_key_exists('odpHuurder', $options['enabled_filters'])) {
            $builder->add('odpHuurder', OdpHuurderFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['odpHuurder'],
            ]);
        }

        if (array_key_exists('odpVerhuurder', $options['enabled_filters'])) {
            $builder->add('odpVerhuurder', OdpVerhuurderFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['odpVerhuurder'],
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
