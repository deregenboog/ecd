<?php

namespace ScipBundle\Form;

use AppBundle\Form\FilterType;
use AppBundle\Form\JaNeeType;
use ScipBundle\Filter\ProjectFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectFilterType extends AbstractType
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

        if (in_array('kpl', $options['enabled_filters'])) {
            $builder->add('kpl', null, [
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
            'data_class' => ProjectFilter::class,
            'enabled_filters' => ['id', 'naam', 'kpl', 'actief'],
        ]);
    }
}
