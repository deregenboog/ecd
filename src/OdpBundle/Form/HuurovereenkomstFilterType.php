<?php

namespace OdpBundle\Form;

use AppBundle\Form\FilterType;
use AppBundle\Form\AppDateRangeType;
use Symfony\Component\Form\AbstractType;
use OdpBundle\Filter\HuurovereenkomstFilter;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\KlantFilterType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use OdpBundle\Entity\Huurovereenkomst;
use AppBundle\Entity\Medewerker;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class HuurovereenkomstFilterType extends AbstractType
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

        if (array_key_exists('huurderKlant', $options['enabled_filters'])) {
            $builder->add('huurderKlant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['huurderKlant'],
            ]);
        }

        if (array_key_exists('verhuurderKlant', $options['enabled_filters'])) {
            $builder->add('verhuurderKlant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['verhuurderKlant'],
            ]);
        }

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(Huurovereenkomst::class, 'huurovereenkomst', 'WITH', 'huurovereenkomst.medewerker = medewerker')
                        ->orderBy('medewerker.voornaam', 'ASC')
                    ;
                },
            ]);
        }

        if (in_array('startdatum', $options['enabled_filters'])) {
            $builder->add('startdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('opzegdatum', $options['enabled_filters'])) {
            $builder->add('opzegdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('einddatum', $options['enabled_filters'])) {
            $builder->add('einddatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('afsluitdatum', $options['enabled_filters'])) {
            $builder->add('afsluitdatum', AppDateRangeType::class, [
                'required' => false,
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
            'data_class' => HuurovereenkomstFilter::class,
        ]);
    }
}
