<?php

namespace DagbestedingBundle\Form;

use DagbestedingBundle\Entity\Trajectcoach;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedewerkerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('medewerker')
                    ->innerJoin(Trajectcoach::class, 'trajectcoach', 'WITH', 'trajectcoach.medewerker = medewerker AND trajectcoach.actief = 1')
                    ->orderBy('medewerker.voornaam')
                    ;
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return \AppBundle\Form\MedewerkerType::class;
    }

    public function getBlockPrefix()
    {
        return 'dagbesteding_medewerker';
    }
}
