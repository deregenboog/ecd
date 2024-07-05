<?php

namespace ScipBundle\Form;

use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedewerkerSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'preset' => false,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('medewerker')
                    ->where('medewerker.roles LIKE :scip OR medewerker.roles LIKE :scip_beheer')
                    ->andWhere('medewerker.actief = true')
                    ->orderBy('medewerker.voornaam')
                    ->setParameter('scip', '%"ROLE_SCIP"%')
                    ->setParameter('scip_beheer', '%"ROLE_SCIP_BEHEER"%')
                ;
            },
        ]);
    }

    public function getParent(): ?string
    {
        return MedewerkerType::class;
    }
}
