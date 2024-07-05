<?php

namespace InloopBundle\Form;

use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Frequentie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FrequentieSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Frequentie::class,
            'required' => false,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('frequentie')
                    ->where('frequentie.datumVan <= DATE(CURRENT_TIMESTAMP())')
                    ->andWhere('frequentie.datumTot IS NULL OR frequentie.datumTot > DATE(CURRENT_TIMESTAMP())')
                    ->orderBy('frequentie.id')
                ;
            },
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
