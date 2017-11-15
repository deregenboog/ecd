<?php

namespace ClipBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use ClipBundle\Entity\Communicatiekanaal;
use Symfony\Component\OptionsResolver\Options;

class CommunicatiekanaalSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'current' => null,
            'class' => Communicatiekanaal::class,
            'placeholder' => '',
            'query_builder' => function (Options $options) {
                function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('communicatiekanaal')
                        ->where('communicatiekanaal.actief = true OR communicatiekanaal = :current')
                        ->orderBy('communicatiekanaal.naam')
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
