<?php

namespace HsBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use HsBundle\Filter\BetalingFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BetalingFilterType extends AbstractType
{
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

    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
