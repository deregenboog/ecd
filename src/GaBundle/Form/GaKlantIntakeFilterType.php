<?php

namespace GaBundle\Form;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityRepository;
use GaBundle\Entity\GaKlantIntake;
use GaBundle\Filter\GaKlantIntakeFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\KlantFilterType;
use AppBundle\Form\FilterType;
use AppBundle\Form\AppDateRangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GaKlantIntakeFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
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

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(GaKlantIntake::class, 'gaKlantIntake', 'WITH', 'gaKlantIntake.medewerker = medewerker')
                        ->orderBy('medewerker.achternaam', 'ASC')
                        ;
                },
            ]);
        }

        if (in_array('afsluitdatum', $options['enabled_filters'])) {
            $builder->add('afsluitdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        $builder->add('filter', SubmitType::class, [
            'label' => 'Filteren',
        ]);

        $builder->add('download', SubmitType::class, [
            'label' => 'Downloaden',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GaKlantIntakeFilter::class,
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
