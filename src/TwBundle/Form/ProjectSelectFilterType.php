<?php

namespace TwBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Entity\Project;

class ProjectSelectFilterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => '',
            'class' => Project::class,
            'required' => false,
            'multiple' => true,
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

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
