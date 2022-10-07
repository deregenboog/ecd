<?php

namespace DagbestedingBundle\Form;

use AppBundle\Entity\Medewerker;
use DagbestedingBundle\Entity\Traject;
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
            'class' => Trajectcoach::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('trajectcoach')
                    ->innerJoin(Medewerker::class, 'medewerker', 'WITH', 'trajectcoach.medewerker = medewerker AND trajectcoach.actief = 1')
                    ->orderBy('medewerker.voornaam')
                ;
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function ddconfigureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

            'placeholder' => 'Selecteer een medewerker',
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('medewerker')
                    ->where('medewerker.actief = true')
                    ->orderBy('medewerker.voornaam');
            },
            'preset' => true,
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
