<?php

namespace App\DagbestedingBundle\Form;

use DagbestedingBundle\Entity\Trajectsoort;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrajectsoortSelectType extends AbstractType
{
    public function getParent(): ?string
    {
        return EntityType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Trajectsoort::class,
            'placeholder' => 'Kies een trajectsoort',
            'required' => false,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) {
                    $builder = $repository->createQueryBuilder('trajectsoort')
                        ->where('trajectsoort.actief = true')
                        ->orderBy('trajectsoort.naam')
                    ;

                    return $builder;
                };
            },
        ]);
    }
}
