<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Filter\KlantFilter;

class KlantFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Nummer'],
            ]);
        }

        if (in_array('naam', $options['enabled_filters'])) {
            $builder->add('naam', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Naam'],
            ]);
        }

        if (in_array('bsn', $options['enabled_filters'])) {
            $builder->add('bsn', null, [
                'required' => false,
            ]);
        }

        if (in_array('geboortedatum', $options['enabled_filters'])) {
            $builder->add('geboortedatum', AppDateType::class, [
                'required' => false,
            ]);
        }

        if (in_array('geboortedatumRange', $options['enabled_filters'])) {
            $builder->add('geboortedatumRange', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('stadsdeel', $options['enabled_filters'])) {
            $builder->add('stadsdeel', StadsdeelFilterType::class);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => KlantFilter::class,
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
