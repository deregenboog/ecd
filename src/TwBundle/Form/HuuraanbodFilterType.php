<?php

namespace TwBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use TwBundle\Entity\Huuraanbod;
use TwBundle\Entity\Klant;
use TwBundle\Entity\Verhuurder;
use TwBundle\Filter\HuuraanbodFilter;
use TwBundle\Filter\HuurovereenkomstFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HuuraanbodFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'required' => false,
            ]);
        }

        if (array_key_exists('appKlant', $options['enabled_filters'])) {
            $builder->add('appKlant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['appKlant'],
            ]);
        }

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
        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', \TwBundle\Form\MedewerkerType::class, [
                'required' => false,
                'preset'=>false
//                'query_builder' => function (EntityRepository $repository) {
//                    return $repository->createQueryBuilder('medewerker')
//                        ->innerJoin(Huuraanbod::class, 'huuraanbod', 'WITH', 'huuraanbod.medewerker = medewerker')
//                        ->orderBy('medewerker.voornaam');
//                },
            ]);
        }
//        if (in_array('actief', $options['enabled_filters'])) {
//            $builder->add('actief', CheckboxType::class, [
//                'required' => false,
//                'label' => 'Actieve huuraanbiedingen',
//                'data' => false,
//            ]);
//        }
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
            'data_class' => HuuraanbodFilter::class,
            'data' => new HuuraanbodFilter(),
            'enabled_filters' => [
                'id',
                'appKlant' => ['naam', 'stadsdeel'],
                'startdatum',
                'afsluitdatum',
                'actief',
                'medewerker',
                'project',
                'huurovereenkomst'=>['isReservering'],

            ],
        ]);
    }
}
