<?php

namespace TwBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\StadsdeelSelectType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Entity\Verhuurder;
use TwBundle\Filter\VerhuurderFilter;

class VerhuurderFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('appKlant', $options['enabled_filters'])) {
            $builder->add('appKlant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['appKlant'],
            ]);
            StadsdeelSelectType::$showOnlyZichtbaar = 0;
        }

        if (in_array('wpi', $options['enabled_filters'])) {
            $builder->add('wpi', CheckboxType::class, [
                'required' => false,
            ]);
        }

        if (in_array('ksgw', $options['enabled_filters'])) {
            $builder->add('ksgw', CheckboxType::class, [
                'required' => false,
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
            $builder->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Actief' => VerhuurderFilter::STATUS_ACTIVE,
                    'Niet actief' => VerhuurderFilter::STATUS_NON_ACTIVE,
                ],
                'required' => false,
            ]);
        }

        if (in_array('gekoppeld', $options['enabled_filters'])) {
            $builder->add('gekoppeld', ChoiceType::class, [
                'label' => 'Gekoppeld?',
                'choices' => [
                    'Onbekend' => null,
                    'Gekoppeld' => true,
                    'Niet gekoppeld' => false,
                ],
                'required' => false,
                'data' => null,
            ]);
        }

        if (in_array('ambulantOndersteuner', $options['enabled_filters'])) {
            $builder->add('ambulantOndersteuner', MedewerkerType::class, [
                'required' => false,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('medewerker')
                        ->innerJoin(Verhuurder::class, 'verhuurder', 'WITH', 'verhuurder.ambulantOndersteuner = medewerker')
                        ->orderBy('medewerker.voornaam');
                },
            ]);
        }

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', MedewerkerType::class, [
                'required' => false,
                'preset' => false,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('medewerker')
                        ->innerJoin(Verhuurder::class, 'verhuurder', 'WITH', 'verhuurder.medewerker = medewerker')
                        ->orderBy('medewerker.voornaam');
                },
            ]);
        }

        if (in_array('project', $options['enabled_filters'])) {
            $builder->add('project', ProjectSelectFilterType::class, [
                'label' => 'Project',
                'required' => false,
//                'data' => false,
            ]);
        }

        $builder
            ->add('filter', SubmitType::class, ['label' => 'Filteren'])
            ->add('download', SubmitType::class, ['label' => 'Downloaden'])
        ;
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VerhuurderFilter::class,
            'data' => new VerhuurderFilter(),
            'enabled_filters' => [
                'appKlant' => ['naam'],
                'aanmelddatum',
//                'afsluitdatum',
                'status',
                'gekoppeld',
//                'wpi',
//                'ksgw',
                'medewerker',
                'project',
            ],
        ]);
    }
}
