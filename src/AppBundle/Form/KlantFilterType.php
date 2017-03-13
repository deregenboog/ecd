<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Filter\KlantFilter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
        if (in_array('geboortedatum', $options['enabled_filters'])) {
            $builder->add('geboortedatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }
        if (in_array('stadsdeel', $options['enabled_filters'])) {
            $builder->add('stadsdeel', StadsdeelFilterType::class);
        }

        if (in_array('filter', $options['enabled_filters'])) {
            $builder->add('filter', SubmitType::class);
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
