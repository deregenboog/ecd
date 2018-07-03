<?php

namespace InloopBundle\Form;

use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Locatie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GebruikersruimteSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return LocatieSelectType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Locatie::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('locatie')
                    ->where("locatie.datumVan <> '0000-00-00' AND locatie.datumVan <= :today")
                    ->andWhere("locatie.datumTot IS NULL OR locatie.datumTot = '0000-00-00' OR locatie.datumTot >= :today")
                    ->andWhere('locatie.gebruikersruimte = true')
                    ->orderBy('locatie.naam')
                    ->setParameter('today', new \DateTime('today'))
                ;
            },
        ]);
    }
}
