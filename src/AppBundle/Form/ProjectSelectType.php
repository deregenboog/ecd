<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => '',
            'class' => Project::class,
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

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
