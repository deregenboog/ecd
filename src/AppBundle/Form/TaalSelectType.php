<?php

namespace AppBundle\Form;

use AppBundle\Entity\Taal;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaalSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Taal::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('taal')->orderBy('taal.naam');
            },
            'preferred_choices' => function (Taal $taal) {
                return $taal->isFavoriet();
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
