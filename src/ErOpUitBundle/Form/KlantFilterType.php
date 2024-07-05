<?php

namespace ErOpUitBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use ErOpUitBundle\Filter\KlantFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', AppKlantFilterType::class, [
                'required' => false,
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (in_array('inschrijfdatum', $options['enabled_filters'])) {
            $builder->add('inschrijfdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('uitschrijfdatum', $options['enabled_filters'])) {
            $builder->add('uitschrijfdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('actief', $options['enabled_filters'])) {
            $builder->add('actief', CheckboxType::class, [
                'label' => 'Alleen actieve dossiers',
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => KlantFilter::class,
            'data' => new KlantFilter(),
            'enabled_filters' => [
                'klant' => ['id', 'naam', 'stadsdeel'],
                'inschrijfdatum',
                'uitschrijfdatum',
                'actief',
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
