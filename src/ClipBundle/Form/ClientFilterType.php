<?php

namespace ClipBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\StadsdeelSelectType;
use ClipBundle\Filter\ClientFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientFilterType extends AbstractType
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

        if (in_array('naam', $options['enabled_filters'])) {
            $builder->add('naam', null, [
                'required' => false,
            ]);
        }

        if (in_array('stadsdeel', $options['enabled_filters'])) {
            $builder->add('stadsdeel', StadsdeelSelectType::class, [
            ]);
        }

        if (in_array('aanmelddatum', $options['enabled_filters'])) {
            $builder->add('aanmelddatum', AppDateRangeType::class, [
                'required' => false,
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
            'data_class' => ClientFilter::class,
            'enabled_filters' => [
                'id',
                'naam',
                'stadsdeel',
                'aanmelddatum',
                'filter',
                'download',
            ],
        ]);
    }
}
