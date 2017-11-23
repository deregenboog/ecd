<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Form\FilterType;
use HsBundle\Filter\FactuurFilter;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateRangeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FactuurFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('nummer', $options['enabled_filters'])) {
            $builder->add('nummer', null, [
                'attr' => ['placeholder' => 'Factuurnummer'],
            ]);
        }

        if (in_array('datum', $options['enabled_filters'])) {
            $builder->add('datum', AppDateRangeType::class, ['required' => false]);
        }

        if (in_array('bedrag', $options['enabled_filters'])) {
            $builder->add('bedrag', MoneyType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Bedrag'],
            ]);
        }

        if (in_array('status', $options['enabled_filters'])) {
            $builder->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Concept' => 0,
                    'Definitief' => 1,
                ],
            ]);
        }

        if (in_array('negatiefSaldo', $options['enabled_filters'])) {
            $builder->add('negatiefSaldo', CheckboxType::class, [
                'required' => false,
                'label' => 'Met openstaande bedrag',
            ]);
        }

        if (in_array('metHerinnering', $options['enabled_filters'])) {
            $builder->add('metHerinnering', CheckboxType::class, [
                'required' => false,
                'label' => 'Met betalingsherinnering',
            ]);
        }

        if (key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, ['enabled_filters' => $options['enabled_filters']['klant']]);
        }

        if (in_array('zipDownload', $options['enabled_filters'])) {
            $builder->add('zipDownload', SubmitType::class);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FactuurFilter::class,
            'enabled_filters' => [
                'nummer',
                'datum',
                'bedrag',
                'status',
                'negatiefSaldo',
                'metHerinnering',
                'klant' => ['naam'],
                'filter',
                'download',
                'zipDownload',
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
