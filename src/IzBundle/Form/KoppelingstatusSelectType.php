<?php

namespace IzBundle\Form;

use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpvraagsoort;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use IzBundle\Entity\Koppelingstatus;

class KoppelingstatusSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => '',
            'class' => Koppelingstatus::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('status')
                    ->where('status.actief = true')
                    ->orderBy('status.naam')
                ;
            },
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
