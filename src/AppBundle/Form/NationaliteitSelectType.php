<?php

namespace AppBundle\Form;

use AppBundle\Entity\Nationaliteit;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NationaliteitSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Nationaliteit::class,
            'placeholder' => 'Selecteer een nationaliteit',
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('nationaliteit')
                    ->orderBy('nationaliteit.naam');
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
