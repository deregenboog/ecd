<?php

namespace ClipBundle\Form;

use ClipBundle\Entity\Locatie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocatieSelectType extends AbstractType
{
    public function getParent(): ?string
    {
        return EntityType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Locatie::class,
            'placeholder' => '',
            'query_builder' => function (EntityRepository $repository) {
                $builder = $repository->createQueryBuilder('locatie')
                    ->where('locatie.datumVan <= DATE(CURRENT_TIMESTAMP())')
                    ->andWhere('locatie.datumTot IS NULL OR locatie.datumTot >= DATE(CURRENT_TIMESTAMP())')
                    ->orderBy('locatie.naam')
                ;

                return $builder;
            },
        ]);
    }
}
