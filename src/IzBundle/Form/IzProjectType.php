<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use IzBundle\Entity\IzProject;
use Doctrine\ORM\EntityRepository;

class IzProjectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => IzProject::class,
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->where('p.startdatum <= :now')
                    ->andWhere('p.einddatum IS NULL OR p.einddatum > :now')
                    ->orderBy('p.naam', 'ASC')
                    ->setParameter('now', new \DateTime())
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
