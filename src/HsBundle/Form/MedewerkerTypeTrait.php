<?php

namespace HsBundle\Form;

use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;

trait MedewerkerTypeTrait
{
    private function addMedewerkerType(FormBuilderInterface $builder, array $options)
    {
        $builder->add('medewerker', MedewerkerType::class, [
            'query_builder' => function (EntityRepository $repository) use ($options) {
                $current = $options['data'] ? $options['data']->getMedewerker() : null;

                return $repository->createQueryBuilder('medewerker')
                    ->where('medewerker = :current')
                    ->orWhere('medewerker.actief = true AND medewerker.roles LIKE :role')
                    ->setParameter('current', $current)
                    ->setParameter('role', '%ROLE_HOMESERVICE%')
                    ->orderBy('medewerker.voornaam')
                ;
            },
        ]);
    }
}
