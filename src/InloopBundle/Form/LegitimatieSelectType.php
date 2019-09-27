<?php

namespace InloopBundle\Form;

use AppBundle\Entity\Legitimatie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LegitimatieSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Legitimatie::class,
            'required' => false,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('legitimatie')
                    ->where('legitimatie.datumVan <= DATE(NOW())')
                    ->andWhere('legitimatie.datumTot IS NULL OR legitimatie.datumTot > DATE(NOW())')
                    ->orderBy('legitimatie.id')
                ;
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }
}
