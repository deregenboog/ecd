<?php

namespace HsBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use HsBundle\Filter\FactuurFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            $builder
                ->add('klant', KlantFilterType::class, [
                    'enabled_filters' => $options['enabled_filters']['klant'],
                ])
                ->get('klant')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    $data = $event->getData();
                    $data->status = null;
                    $event->setData($data);
                })
            ;
        }

        if (in_array('zipDownload', $options['enabled_filters'])) {
            $builder->add('zipDownload', SubmitType::class);
        }

        if (in_array('pdfDownload', $options['enabled_filters'])) {
            $builder->add('pdfDownload', SubmitType::class);
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
                'klant' => ['naam', 'afwijkendFactuuradres'],
                'filter',
                'download',
                'zipDownload',
                'pdfDownload',
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
