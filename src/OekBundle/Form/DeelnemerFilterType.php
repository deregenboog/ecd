<?php

namespace OekBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\JaNeeType;
use AppBundle\Form\KlantFilterType;
use OekBundle\Entity\Groep;
use OekBundle\Entity\Training;
use OekBundle\Filter\DeelnemerFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeelnemerFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'attr' => ['placeholder' => 'Klantnummer'],
            ]);
        }

        if (key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, ['enabled_filters' => $options['enabled_filters']['klant']]);
        }

        if (in_array('groep', $options['enabled_filters'])) {
            $builder->add('groep', EntityType::class, [
                'required' => false,
                'class' => Groep::class,
            ]);
        }

        if (in_array('training', $options['enabled_filters'])) {
            $builder->add('training', EntityType::class, [
                'required' => false,
                'class' => Training::class,
            ]);
        }

        if (in_array('heeftAfgerondeTraining', $options['enabled_filters'])) {
            $builder->add('heeftAfgerondeTraining', JaNeeType::class, [
                'required' => false,
                'expanded' => false,
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

        if (in_array('status', $options['enabled_filters'])) {
            $builder->add('actief', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Actief' => 'true',
                    'Niet actief' => 'false',
                ],
            ]);
        }

        $builder->add('filter', SubmitType::class, [
            'label' => 'Filteren',
        ]);

        $builder->add('download', SubmitType::class, [
            'label' => 'Downloaden',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeelnemerFilter::class,
            'data' => new DeelnemerFilter(),
            'enabled_filters' => [
                'klant' => ['id', 'naam', 'stadsdeel'],
                'training',
                'heeftAfgerondeTraining',
                'aanmelddatum',
                'afsluitdatum',
                'status',
            ],
        ]);
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
