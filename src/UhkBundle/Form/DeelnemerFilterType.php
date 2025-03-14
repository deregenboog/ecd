<?php

namespace UhkBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType;
use AppBundle\Form\MedewerkerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UhkBundle\Filter\DeelnemerFilter;

class DeelnemerFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('projecten', $options['enabled_filters'])) {
            $builder->add('projecten', ProjectSelectType::class, [
                'required' => false,
            ]);
        }

        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (in_array('aanmelddatum', $options['enabled_filters'])) {
            $builder->add('aanmelddatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', MedewerkerType::class, [
                'required' => false,
                'preset' => false,
            ]);
        }

        if (in_array('actief', $options['enabled_filters'])) {
            $builder->add('actief', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen actieve deelnemers tonen',
            ]);
        }

        $builder
            ->add('filter', SubmitType::class, ['label' => 'Filteren'])
        ;
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeelnemerFilter::class,
            'data' => new DeelnemerFilter(),
            'enabled_filters' => [
                'klant' => ['id', 'naam', 'stadsdeel'],
                'aanmelddatum',
                'medewerker',
                'filter',
                'actief',
                'projecten'=> ['color', 'naam', 'uhk_project_id']
            ],
        ]);
    }
}
