<?php

namespace UhkBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UhkBundle\Entity\Project;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ProjectSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => '',
            'class' => Project::class,
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->andWhere('p.actief = true')
                    ->orderBy('p.naam', 'ASC')
                ;
            },
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
