<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Filter\KlantFilter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Geslacht;

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
                'label' => 'Nummer',
                'attr' => ['placeholder' => 'Nummer'],
            ]);
        }

        if (in_array('naam', $options['enabled_filters'])) {
            $builder->add('naam', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Naam'],
            ]);
        }

        if (in_array('voornaam', $options['enabled_filters'])) {
            $builder->add('voornaam', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Voornaam'],
            ]);
        }

        if (in_array('achternaam', $options['enabled_filters'])) {
            $builder->add('achternaam', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Achternaam'],
            ]);
        }

        if (in_array('geslacht', $options['enabled_filters'])) {
            $builder->add('geslacht', EntityType::class, [
                'class' => Geslacht::class,
                'required' => false,
            ]);
        }

        if (in_array('bsn', $options['enabled_filters'])) {
            $builder->add('bsn', null, [
                'required' => false,
            ]);
        }

        if (in_array('geboortedatum', $options['enabled_filters'])) {
            $builder->add('geboortedatum', AppDateType::class, [
                'required' => false,
            ]);
        }

        if (in_array('geboortedatumRange', $options['enabled_filters'])) {
            $builder->add('geboortedatumRange', AppDateRangeType::class, [
                'required' => false,
                'label' => false,
            ]);
        }

        if (in_array('stadsdeel', $options['enabled_filters'])) {
            $builder->add('stadsdeel', StadsdeelSelectType::class);
        }

        if (in_array('postcodegebied', $options['enabled_filters'])) {
            $builder->add('postcodegebied', PostcodegebiedSelectType::class);
        }

        if (in_array('plaats', $options['enabled_filters'])) {
            $builder->add('plaats');
        }

        $builder->add('filter', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => KlantFilter::class,
            'enabledFilters' => [
                'klant' => ['id', 'naam', 'stadsdeel'],
                'aanmelddatum',
                'afsluitdatum',
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
