<?php

namespace OekraineBundle\Form;

use Doctrine\ORM\EntityRepository;
use OekraineBundle\Entity\SchorsingReden;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RedenSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
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
