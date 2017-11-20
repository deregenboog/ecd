<?php

namespace ClipBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use ClipBundle\Entity\Leeftijdscategorie;
use Symfony\Component\OptionsResolver\Options;

class LeeftijdscategorieSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'current' => null,
            'class' => Leeftijdscategorie::class,
            'placeholder' => '',
            'query_builder' => function (Options $options) {
                function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('leeftijdscategorie')
                        ->where('leeftijdscategorie.actief = true OR leeftijdscategorie = :current')
                        ->orderBy('leeftijdscategorie.naam')
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
