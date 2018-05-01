<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Filter\ZrmFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ZrmFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'required' => false,
                'label' => 'Nummer',
                'attr' => ['placeholder' => 'Nummer'],
            ]);
        }

        if (in_array('created', $options['enabled_filters'])) {
            $builder->add('created', AppDateRangeType::class, [
                'required' => false,
                'label' => false,
            ]);
        }

        if (in_array('requestModule', $options['enabled_filters'])) {
            $builder->add('requestModule', ChoiceType::class, [
                'required' => false,
                'label' => false,
                'choices' => [
                    'GroepsactiviteitenIntake' => 'GroepsactiviteitenIntake',
                    'Hi5' => 'Hi5',
                    'Intake' => 'Intake',
                    'IzIntake' => 'IzIntake',
                    'Klant' => 'Klant',
                    'MaatschappelijkWerk' => 'MaatschappelijkWerk',
                ],
            ]);
        }

        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        $builder->add('filter', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ZrmFilter::class,
            'enabled_filters' => [
                'created',
                'requestModule',
                'klant' => ['id', 'naam'],
            ],
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
