<?php

namespace TwBundle\Form;

use Doctrine\ORM\EntityRepository;
use TwBundle\Entity\Klant;
use TwBundle\Entity\Project;
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
            'multiple'=>true,
//            'query_builder' => function (EntityRepository $repo) {
//                $builder = $repo->createQueryBuilder('p');
//                $builder
//                    ->where($builder->expr()->andX(
//                        'p.startdatum <= :now',
//                        'p.einddatum IS NULL OR p.einddatum > :now'
//                    ))
//
//                    ->setParameter('now', new \DateTime())
//                ;
//                return $builder;
//            },
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
