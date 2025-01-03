<?php

namespace AppBundle\Form;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => '',
            'current' => null,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('entity')
                        ->where('entity.actief = true')
                        ->orderBy('entity.naam')
                        ->setParameter('current', $options['current']);

                    if (is_array($options['current']) || $options['current'] instanceof Collection) {
                        return $builder->orWhere('entity IN (:current)');
                    }

                    return $builder->orWhere('entity = :current');
                };
            },
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
