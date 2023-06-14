<?php

namespace GaBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\StadsdeelSelectType;
use GaBundle\Filter\GroepFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroepFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('naam', $options['enabled_filters'])) {
            $builder->add('naam', null, [
                'required' => false,
            ]);
        }

        if (in_array('werkgebied', $options['enabled_filters'])) {
            $builder->add('werkgebied', StadsdeelSelectType::class, [
                'required' => false,
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GroepFilter::class,
            'enabled_filters' => [
                'naam',
                'werkgebied',
                'startdatum',
                'einddatum',
                'filter',
                'download',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
