<?php

namespace GaBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use Doctrine\ORM\EntityRepository;
use GaBundle\Entity\GaKlantIntake;
use GaBundle\Filter\GaIntakeFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GaIntakeFilterType extends AbstractType
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

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(GaKlantIntake::class, 'gaKlantIntake', 'WITH', 'gaKlantIntake.medewerker = medewerker')
                        ->where('medewerker.actief = :true')
                        ->setParameter('true', true)
                        ->orderBy('medewerker.voornaam', 'ASC')
                    ;
                },
            ]);
        }

        if (in_array('intakedatum', $options['enabled_filters'])) {
            $builder->add('intakedatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('afsluitdatum', $options['enabled_filters'])) {
            $builder->add('afsluitdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('open', $options['enabled_filters'])) {
            $builder->add('open', CheckboxType::class, [
                'required' => false,
                'label' => 'Zonder afsluitdatum',
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
            'data_class' => GaIntakeFilter::class,
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
