<?php

namespace InloopBundle\Form;

use AppBundle\Entity\Verblijfsstatus;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerblijfsstatusSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Verblijfsstatus::class,
            'placeholder' => '',
            'required' => false,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('verblijfsstatus')
                    ->where('verblijfsstatus.datumVan <= DATE(CURRENT_TIMESTAMP())')
                    ->andWhere('verblijfsstatus.datumTot IS NULL OR verblijfsstatus.datumTot > DATE(CURRENT_TIMESTAMP())')
                    ->orderBy('verblijfsstatus.id')
                ;
            },
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
