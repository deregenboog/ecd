<?php

namespace IzBundle\Form;

use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Koppeling;
use IzBundle\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectSelectFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => '',
            'class' => Project::class,
            'required'=>false,
            'query_builder' => function (EntityRepository $repo) {
                $builder = $repo->createQueryBuilder('p');
                $builder
                    ->leftJoin(Hulpvraag::class,"koppeling", "WITH","koppeling.project = p")
                    ->where("koppeling.id IS NOT NULL")
                    ->andWhere("koppeling.einddatum IS NULL")
                    ->andWhere("koppeling.hulpaanbod IS NULL")
//                    ->andWhere("koppeling.afsluiting IS NULL")
                    ->orWhere($builder->expr()->andX(
                        'p.startdatum <= :now',
                        'p.einddatum IS NULL OR p.einddatum > :now'
                    ))
                    ->orderBy('p.naam', 'ASC')
                    ->setParameter('now', new \DateTime())
                ;
                return $builder;
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
