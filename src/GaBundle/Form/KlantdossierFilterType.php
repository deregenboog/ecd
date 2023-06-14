<?php

namespace GaBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType;
use GaBundle\Entity\Activiteit;
use GaBundle\Filter\KlantdossierFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantdossierFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (in_array('groep', $options['enabled_filters'])) {
            $builder->add('groep', GroepSelectType::class, [
                'required' => false,
            ]);
        }

        if (in_array('activiteit', $options['enabled_filters'])) {
            $builder->add('activiteit', EntityType::class, [
                'required' => false,
                'class' => Activiteit::class,
            ]);
        }

        if (in_array('aanmelddatum', $options['enabled_filters'])) {
            $builder->add('aanmelddatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('afsluitdatum', $options['enabled_filters'])) {
            $builder->add('afsluitdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('actief', $options['enabled_filters'])) {
            $builder->add('actief', CheckboxType::class, [
                'label' => 'Alleen actieve dossiers',
                'required' => false,
            ]);
        }

        $builder->add('filter', SubmitType::class, [
            'label' => 'Filteren',
        ]);

        $builder->add('download', SubmitType::class, [
            'label' => 'Downloaden',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => KlantdossierFilter::class,
            'data' => new KlantdossierFilter(),
            'enabled_filters' => [
                'klant' => ['id', 'naam', 'stadsdeel'],
                'groep',
                'aanmelddatum',
                'afsluitdatum',
                'actief',
                'filter',
                'download',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
