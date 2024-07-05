<?php

namespace IzBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Intake;
use IzBundle\Filter\IntervisiegroepFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntervisiegroepFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'required' => false,
                'label' => false,
            ]);
        }

        if (in_array('naam', $options['enabled_filters'])) {
            $builder->add('naam', null, [
                'required' => false,
                'label' => false,
            ]);
        }

        if (in_array('startdatum', $options['enabled_filters'])) {
            $builder->add('startdatum', AppDateRangeType::class, [
                'required' => false,
                'label' => false,
            ]);
        }

        if (in_array('actief', $options['enabled_filters'])) {
            $builder->add('actief', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen actieve groepen',
            ]);
        }

        if (in_array('einddatum', $options['enabled_filters'])) {
            $builder->add('einddatum', AppDateRangeType::class, [
                'required' => false,
                'label' => false,
            ]);
        }

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'label' => 'Medewerker intake',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(Intake::class, 'intake', 'WITH', 'intake.medewerker = medewerker')
                        ->where('medewerker.actief = :true')
                        ->setParameter('true', true)
                        ->orderBy('medewerker.voornaam', 'ASC')
                    ;
                },
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IntervisiegroepFilter::class,
            'data' => new IntervisiegroepFilter(),
            'enabled_filters' => [
                'id',
                'naam',
                'startdatum',
                'actief',
                'einddatum',
                'medewerker',
                'filter',
                'download',
            ],
        ]);
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
