<?php

namespace IzBundle\Form;

use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Doelgroep;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoelgroepSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => '',
            'expanded' => true,
            'class' => Doelgroep::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('doelgroep')
                    ->where('doelgroep.actief = true')
                    ->orderBy('doelgroep.naam')
                ;
            },
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
