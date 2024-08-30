<?php

namespace AppBundle\Form;

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
                    ->where('medewerker.actief = true')
                    ->orderBy('medewerker.voornaam')

                ;
            },
        ]);
    }

    public function getParent(): ?string
    {
        return MedewerkerType::class;
    }
}
