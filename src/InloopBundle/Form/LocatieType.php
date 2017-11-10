<?php

namespace InloopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use InloopBundle\Entity\Locatie;
use Doctrine\ORM\EntityRepository;

class LocatieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Locatie::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('locatie')
                    ->orderBy('locatie.naam')
                ;
            },
        ]);
    }
}
