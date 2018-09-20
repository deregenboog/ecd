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

                /*
                 * @todo Remove the use of ldap groups, just check roles here.
                 * This is only needed in the transition phase (production is using
                 * CakePHP authorization and beta uses Symfony authorization).
                 */

                return $repository->createQueryBuilder('medewerker')
                    ->where('medewerker = :current')
                    ->orWhere('medewerker.actief = true AND (
                        medewerker.groepen LIKE :ldap_groups
                        OR medewerker.ldapGroups LIKE :ldap_groups
                        OR medewerker.roles LIKE :role
                    )')
                    ->setParameter('current', $current)
                    ->setParameter('ldap_groups', '%ECD Homeservice%')
                    ->setParameter('role', '%ROLE_HOMESERVICE%')
                    ->orderBy('medewerker.voornaam')
                ;
            },
        ]);
    }
}
