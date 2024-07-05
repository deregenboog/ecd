<?php

namespace InloopBundle\Form;

use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\SchorsingReden;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RedenSelectType extends AbstractType
{
    public function getParent(): ?string
    {
        return EntityType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => SchorsingReden::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('reden')
                    ->orderBy('reden.id')
                ;
            },
        ]);
    }
}
