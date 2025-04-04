<?php

namespace AppBundle\Form;

use AppBundle\Entity\Geslacht;
use AppBundle\Filter\KlantFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantFilterType extends AbstractType
{
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

        if (in_array('adres', $options['enabled_filters'])) {
            $builder->add('adres', null, [
                'required' => false,
            ]);
        }

        if (in_array('geslacht', $options['enabled_filters'])) {
            $builder->add('geslacht', EntityType::class, [
                'class' => Geslacht::class,
                'required' => false,
                'multiple' => true,
            ]);
        }

        if (in_array('bsn', $options['enabled_filters'])) {
            $builder->add('bsn', null, [
                'label' => 'BSN',
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

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', MedewerkerType::class, [
                'required' => false,
                'preset' => false,
            ]);
        }

        if (in_array('maatschappelijkWerker', $options['enabled_filters'])) {
            $builder->add('maatschappelijkWerker', MedewerkerType::class, [
                'required' => false,
                'preset' => false,
            ]);
        }

        if (in_array('stadsdeel', $options['enabled_filters'])) {
            $builder->add('stadsdeel', StadsdeelSelectType::class, [
            ]);
        }

        if (in_array('postcodegebied', $options['enabled_filters'])) {
            $builder->add('postcodegebied', PostcodegebiedSelectType::class);
        }

        if (in_array('plaats', $options['enabled_filters'])) {
            $builder->add('plaats');
        }

        $builder->add('filter', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => KlantFilter::class,
            'enabled_filters' => [
                'id',
                'naam',
                'geslacht',
                'geboortedatum',
                'medewerker',
                'stadsdeel',
                'postcodegebied',
            ],
        ]);
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
