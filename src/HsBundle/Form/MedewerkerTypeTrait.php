<?php

namespace HsBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;

trait MedewerkerTypeTrait
{
    private function addMedewerkerType(FormBuilderInterface $builder, array $options)
    {
        $builder->add('medewerker', MedewerkerType::class, [
            'query_builder' => function (EntityRepository $repository) use ($options) {
                $current = $options['data'] ? $options['data']->getMedewerker() : null;

                return $repository->createQueryBuilder('medewerker')
                    ->where('medewerker = :current')
                    ->orWhere('medewerker.actief = true AND medewerker.groepen LIKE :groups')
                    ->setParameter('current', $current)
                    ->setParameter('groups', '%'.GROUP_HOMESERVICE.'%')
                    ->orderBy('medewerker.voornaam')
                ;
            },
        ]);
    }
}
