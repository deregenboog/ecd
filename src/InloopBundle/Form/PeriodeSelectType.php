<?php

namespace InloopBundle\Form;

use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Periode;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PeriodeSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Periode::class,
            'required' => false,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('periode')
                    ->where('periode.datumVan <= DATE(CURRENT_TIMESTAMP())')
                    ->andWhere('periode.datumTot IS NULL OR periode.datumTot > DATE(CURRENT_TIMESTAMP())')
                    ->orderBy('periode.id')
                ;
            },
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
