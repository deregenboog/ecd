<?php

namespace HsBundle\Form;

use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use HsBundle\Filter\ArbeiderFilter;
use HsBundle\Filter\DienstverlenerFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DienstverlenerFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'attr' => ['placeholder' => 'Klantnummer'],
            ]);
        }

        if (in_array('hulpverlener', $options['enabled_filters'])) {
            $builder->add('hulpverlener', null, [
                'required' => false,
            ]);
        }

        if (in_array('rijbewijs', $options['enabled_filters'])) {
            $builder->add('rijbewijs', CheckboxType::class, [
                'label' => 'Rijbewijs',
                'required' => false,
            ]);
        }

        if (in_array('status', $options['enabled_filters'])) {
            $builder->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    ArbeiderFilter::STATUS_ACTIVE => ArbeiderFilter::STATUS_ACTIVE,
                    ArbeiderFilter::STATUS_NON_ACTIVE => ArbeiderFilter::STATUS_NON_ACTIVE,
                ],
            ]);
        }

        if (key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', AppKlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DienstverlenerFilter::class,
            'data' => new DienstverlenerFilter(),
            'enabled_filters' => [
                'hulpverlener',
                'rijbewijs',
                'status',
                'klant' => ['id', 'naam', 'adres', 'geboortedatumRange', 'stadsdeel'],
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
