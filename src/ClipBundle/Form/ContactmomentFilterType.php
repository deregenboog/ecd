<?php

namespace ClipBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use ClipBundle\Filter\ContactmomentFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactmomentFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, []);
        }

        if (array_key_exists('vraag', $options['enabled_filters'])) {
            $builder->add('vraag', VraagFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['vraag'],
            ]);
        }

        if (in_array('behandelaar', $options['enabled_filters'])) {
            $builder->add('behandelaar', BehandelaarFilterType::class);
        }

        if (in_array('datum', $options['enabled_filters'])) {
            $builder->add('datum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactmomentFilter::class,
            'enabled_filters' => [
                'id',
                'vraag' => ['soort', 'client' => ['naam']],
                'behandelaar',
                'datum',
                'filter',
                'download',
            ],
        ]);
    }
}
