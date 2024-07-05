<?php

namespace MwBundle\Form;

use Doctrine\ORM\EntityRepository;
use MwBundle\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Project',
            'placeholder' => '',
            'class' => Project::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('project')
                    ->orderBy('project.naam');
            },
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
