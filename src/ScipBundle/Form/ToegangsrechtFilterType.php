<?php

namespace ScipBundle\Form;

use AppBundle\Form\FilterType;
use ScipBundle\Filter\ToegangsrechtFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToegangsrechtFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', MedewerkerSelectType::class, [
                'required' => false,
                'placeholder' => '',
            ]);
        }

        if (in_array('project', $options['enabled_filters'])) {
            $builder->add('project', ProjectSelectType::class, [
                'required' => false,
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
            'data_class' => ToegangsrechtFilter::class,
            'enabled_filters' => [
                'medewerker',
                'project',
            ],
        ]);
    }
}
