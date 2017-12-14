<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\FilterType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use HsBundle\Filter\BetalingFilter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateRangeType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class BetalingFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('referentie', $options['enabled_filters'])) {
            $builder->add('referentie', null, [
                'attr' => ['placeholder' => 'Referentie'],
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

        if (in_array('datum', $options['enabled_filters'])) {
            $builder->add('datum', AppDateRangeType::class, ['required' => false]);
        }

        if (in_array('bedrag', $options['enabled_filters'])) {
            $builder->add('bedrag', MoneyType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Bedrag'],
            ]);
        }

        if (key_exists('factuur', $options['enabled_filters'])) {
            $builder->add('factuur', FactuurFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['factuur'],
            ]);
        }

        $builder
            ->add('filter', SubmitType::class)
            ->add('download', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BetalingFilter::class,
            'enabled_filters' => [
                'referentie',
                'datum',
                'bedrag',
                'factuur' => ['nummer'],
                'klant' => ['naam'],
                'filter',
                'download',
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
