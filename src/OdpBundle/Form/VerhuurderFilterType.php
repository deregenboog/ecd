<?php

namespace OdpBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\StadsdeelSelectType;
use Doctrine\ORM\EntityRepository;
use OdpBundle\Entity\Huurder;
use OdpBundle\Entity\Verhuurder;
use OdpBundle\Filter\VerhuurderFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerhuurderFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
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
                        ->innerJoin(Verhuurder::class, 'verhuurder', 'WITH', 'verhuurder.ambulantOndersteuner = medewerker')
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
            'data_class' => VerhuurderFilter::class,
            'data' => new VerhuurderFilter(),
            'enabled_filters' => [
                'klant' => ['id', 'naam', 'stadsdeel'],
                'aanmelddatum',
                'afsluitdatum',
                'actief',
                'wpi',
                'ksgw',
                'ambulantOndersteuner',
                'project'
            ],
        ]);
    }
}
