<?php

namespace InloopBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocatieTypeSelectType extends AbstractType
{
    public function getParent(): ?string
    {
        return EntityType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => \InloopBundle\Entity\LocatieType::class,
            'placeholder' => '',
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) {
                    $builder = $repository->createQueryBuilder('locatie_type')
                        ->orderBy('locatie_type.naam')
                    ;

                    return $builder;
                };
            },
        ]);
    }
}
