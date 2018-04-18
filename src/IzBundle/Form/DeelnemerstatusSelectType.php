<?php

namespace IzBundle\Form;

use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Deelnemerstatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeelnemerstatusSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => true,
            'current' => null,
            'placeholder' => '',
            'class' => Deelnemerstatus::class,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('status')
                    ->where('status.actief = true OR status = :current')
                    ->setParameter('current', $options['current'])
                    ->orderBy('status.naam')
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
