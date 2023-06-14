<?php

namespace ClipBundle\Form;

use ClipBundle\Entity\Behandelaar;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BehandelaarFilterType extends AbstractType
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
            'class' => Behandelaar::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('behandelaar')
                    ->leftJoin('behandelaar.medewerker', 'medewerker')
                    ->where('behandelaar.actief = true')
                    ->orderBy('behandelaar.displayName')
                ;
            },
            'required' => false,
        ]);
    }
}
