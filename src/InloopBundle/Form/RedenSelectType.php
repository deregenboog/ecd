<?php

namespace InloopBundle\Form;

use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Locatie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use InloopBundle\Entity\SchorsingReden;

class RedenSelectType extends AbstractType
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
            'class' => SchorsingReden::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('reden')
                    ->orderBy('reden.id')
                ;
            },
        ]);
    }
}
