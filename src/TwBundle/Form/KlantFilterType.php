<?php

namespace TwBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use AppBundle\Form\StadsdeelSelectType;
use Doctrine\ORM\EntityRepository;
use TwBundle\Entity\Klant;
use TwBundle\Entity\Verhuurder;
use PfoBundle\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Filter\KlantFilter;

class KlantFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('appKlant', $options['enabled_filters'])) {
            $builder->add('appKlant', AppKlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['appKlant'],

            ]);
            StadsdeelSelectType::$showOnlyZichtbaar = 0;
        }

        if (in_array('automatischeIncasso', $options['enabled_filters'])) {
            $builder->add('automatischeIncasso', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Ja' => 1,
                    'Nee' => 0,
                ],
            ]);
        }

        if (in_array('inschrijvingWoningnet', $options['enabled_filters'])) {
            $builder->add('inschrijvingWoningnet', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Ja' => 1,
                    'Nee' => 0,
                ],
            ]);
        }

        if (in_array('waPolis', $options['enabled_filters'])) {
            $builder->add('waPolis', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Ja' => 1,
                    'Nee' => 0,
                ],
            ]);
        }

        if (in_array('wpi', $options['enabled_filters'])) {
            $builder->add('wpi', CheckboxType::class, [
                'required' => false,
            ]);
        }
        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', \TwBundle\Form\MedewerkerType::class, [
                'required' => false,
                'preset'=>false

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
                'data' => false,
            ]);
        }
        if (in_array('ambulantOndersteuner', $options['enabled_filters'])) {
            $builder->add('ambulantOndersteuner', MedewerkerType::class, [
                'required' => false,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('medewerker')
                        ->innerJoin(Klant::class, 'klant', 'WITH', 'klant.ambulantOndersteuner = medewerker')
                        ->orderBy('medewerker.voornaam');
                },
            ]);
        }
        if (in_array('project', $options['enabled_filters'])) {
            $builder->add('project', ProjectSelectFilterType::class, [
                'label' => 'Project',
                'required' => false,
                'data' => false,
            ]);
        }

        $builder
            ->add('filter', SubmitType::class, ['label' => 'Filteren'])
            ->add('download', SubmitType::class, ['label' => 'Downloaden'])
        ;
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
            'data_class' => KlantFilter::class,
            'data' => new KlantFilter(),
            'enabled_filters' => [
                'appKlant' => ['id', 'naam', 'stadsdeel'],
                'automatischeIncasso',
                'inschrijvingWoningnet',
                'waPolis',
                'project',
                'aanmelddatum',
                'afsluitdatum',
                'actief',
                'wpi',
                'medewerker',
                'ambulantOndersteuner',
            ],
        ]);
    }
}
