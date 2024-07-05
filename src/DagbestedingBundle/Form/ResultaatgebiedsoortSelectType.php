<?php

namespace App\DagbestedingBundle\Form;

use DagbestedingBundle\Entity\Resultaatgebiedsoort;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultaatgebiedsoortSelectType extends AbstractType
{
    public function getParent(): ?string
    {
        return EntityType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Resultaatgebiedsoort::class,
            'placeholder' => 'Kies een resultaatgebied',
            'required' => false,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) {
                    $builder = $repository->createQueryBuilder('resultaatgebiedsoort')
                        ->where('resultaatgebiedsoort.actief = true')
                        ->orderBy('resultaatgebiedsoort.naam')
                    ;

                    return $builder;
                };
            },
        ]);
    }
}
