<?php

namespace IzBundle\Form;

use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectSelectFilterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => '',
            'class' => Project::class,
            'required' => false,
            'query_builder' => function (EntityRepository $repo) {
                $builder = $repo->createQueryBuilder('p');
                $builder
                    ->innerJoin(Hulpvraag::class, 'koppeling', 'WITH', 'koppeling.project = p')
//                    ->where("koppeling.id IS NOT NULL")
                    ->where('koppeling.einddatum IS NULL')
                    ->andWhere('koppeling.hulpaanbod IS NULL')
//                    ->andWhere("koppeling.afsluiting IS NULL")
                    ->orWhere($builder->expr()->andX(
                        'p.startdatum <= :now',
                        'p.einddatum IS NULL OR p.einddatum > :now'
                    ))
                    ->orderBy('p.naam', 'ASC')
                    ->setParameter('now', new \DateTime())
                ;

                //                $sql = SqlExtractor::getFullSQL($builder->getQuery());
                return $builder;
            },
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
