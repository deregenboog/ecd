<?php

namespace ClipBundle\Form;

use ClipBundle\Entity\Viacategorie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ViacategorieSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'current' => null,
            'class' => Viacategorie::class,
            'placeholder' => '',
            'query_builder' => function (Options $options) {
                function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('viacategorie')
                        ->where('viacategorie.actief = true OR viacategorie = :current')
                        ->orderBy('viacategorie.naam')
                        ->setParameter('current', $options['current'])
                    ;
                };
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
