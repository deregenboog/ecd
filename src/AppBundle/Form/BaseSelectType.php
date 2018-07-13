<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => '',
            'current' => null,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('entity')
                        ->where('entity.actief = true OR entity = :current')
                        ->setParameter('current', $options['current'])
                        ->orderBy('entity.naam')
                    ;
                };
            },
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
