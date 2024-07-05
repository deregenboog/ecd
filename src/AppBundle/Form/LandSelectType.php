<?php

namespace AppBundle\Form;

use AppBundle\Entity\Land;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LandSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Land::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('land')
                    ->orderBy('land.land');
            },
            'preferred_choices' => function (Land $land) {
                return in_array($land->getNaam(), ['Nederland', 'Onbekend']);
            },
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
