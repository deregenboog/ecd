<?php

namespace InloopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Form\FilterType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use InloopBundle\Entity\Locatie;
use InloopBundle\Filter\SchorsingFilter;
use InloopBundle\Filter\RegistratieFilter;

class RegistratieFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', AppKlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (in_array('locatie', $options['enabled_filters'])) {
            $builder->add('locatie', LocatieType::class, [
                'required' => false,
            ]);
        }

        if (in_array('binnen', $options['enabled_filters'])) {
            $builder->add('binnen', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('buiten', $options['enabled_filters'])) {
            $builder->add('buiten', AppDateRangeType::class, [
                'required' => false,
            ]);
        }
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
            'data_class' => RegistratieFilter::class,
            'enabled_filters' => [
                'klant' => ['id', 'naam', 'geslacht'],
                'locatie',
                'binnen',
                'buiten',
                'filter',
                'download',
            ],
        ]);
    }
}
