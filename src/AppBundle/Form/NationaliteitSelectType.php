<?php

namespace AppBundle\Form;

use AppBundle\Entity\Nationaliteit;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NationaliteitSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Nationaliteit::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('nationaliteit')
                    ->orderBy('nationaliteit.naam');
            },
            'preferred_choices' => function (Nationaliteit $nationaliteit) {
                return in_array($nationaliteit->getNaam(), ['Nederlandse', 'Onbekend']);
            },
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
