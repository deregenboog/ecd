<?php

namespace ScipBundle\Form;

use AppBundle\Form\FilterType;
use AppBundle\Form\JaNeeType;
use ScipBundle\Filter\LabelFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LabelFilterType extends AbstractType
{
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

        if (in_array('actief', $options['enabled_filters'])) {
            $builder->add('actief', JaNeeType::class, [
                'required' => false,
                'expanded' => false,
            ]);
        }

        $builder->add('filter', SubmitType::class, ['label' => 'Filteren']);
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LabelFilter::class,
            'enabled_filters' => ['id', 'naam', 'actief'],
        ]);
    }
}
