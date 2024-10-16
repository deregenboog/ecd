<?php

namespace TwBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Entity\Huurverzoek;
use TwBundle\Entity\Klant;
use TwBundle\Filter\HuurverzoekFilter;

class HuurverzoekFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'required' => false,
            ]);
        }

        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }
        $builder->add('huisgenoot', EntityType::class,
            ['class' => Klant::class,
                'required' => false,
                'placeholder' => '',
            ]
        );
        if (in_array('startdatum', $options['enabled_filters'])) {
            $builder->add('startdatum', AppDateRangeType::class, [
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
                'required' => false,
                'label' => 'Alleen actieve huurverzoeken',
                'data' => true,
//                'empty_data' => ['isActief' => true],
            ]);
        }
        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', \TwBundle\Form\MedewerkerType::class, [
                'required' => false,
                'preset' => false,
//                'query_builder' => function (EntityRepository $repository) {
//                    return $repository->createQueryBuilder('medewerker')
//                        ->innerJoin(Huurverzoek::class, 'huurverzoek', 'WITH', 'huurverzoek.medewerker = medewerker')
//                        ->orderBy('medewerker.voornaam');
//                },
            ]);
        }
        if (array_key_exists('huurovereenkomst', $options['enabled_filters'])) {
            $builder->add('huurovereenkomst', HuurovereenkomstFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['huurovereenkomst'],
                'empty_data' => ['isReservering' => true],
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
            'data_class' => HuurverzoekFilter::class,
            'data' => new HuurverzoekFilter(),
            'enabled_filters' => [
                'klant' => ['naam'],
                'startdatum',
                'actief',
                'project',
                'huurovereenkomst' => ['isReservering', 'isActief'],
            ],
        ]);
    }
}
