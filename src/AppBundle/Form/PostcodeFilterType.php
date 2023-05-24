<?php

namespace AppBundle\Form;

use AppBundle\Filter\PostcodeFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostcodeFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('postcode', $options['enabled_filters'])) {
            $builder->add('postcode');
        }

        if (in_array('stadsdeel', $options['enabled_filters'])) {
            $builder->add('stadsdeel', StadsdeelSelectType::class);
        }

        if (in_array('postcodegebied', $options['enabled_filters'])) {
            $builder->add('postcodegebied', PostcodegebiedSelectType::class);
        }

        $builder->add('filter', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PostcodeFilter::class,
            'enabled_filters' => [
                'postcode',
                'stadsdeel',
                'postcodegebied',
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
