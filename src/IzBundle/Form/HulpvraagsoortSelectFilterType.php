<?php

namespace IzBundle\Form;

use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Hulpvraagsoort;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HulpvraagsoortSelectFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => '',
            'expanded' => true,
            'class' => Hulpvraagsoort::class,
            'query_builder' => function (EntityRepository $repository) {
                $builder = $repository->createQueryBuilder('hulpvraagsoort');
                $builder
                    ->leftJoin(Hulpvraag::class,"koppeling", "WITH","koppeling.hulpvraagsoort = hulpvraagsoort")
                    ->where("koppeling.id IS NOT NULL")
                    ->andWhere("koppeling.einddatum IS NULL")
                    ->andWhere("koppeling.hulpaanbod IS NULL")
                    ->orWhere('hulpvraagsoort.actief = true')

                    ->orderBy('hulpvraagsoort.naam')
                ;
                return $builder;
            },
            'choice_attr' => function (Hulpvraagsoort $hulpvraagsoort) {
                if ($hulpvraagsoort->getToelichting()) {
                    return ['title' => $hulpvraagsoort->getToelichting()];
                }

                return [];
            },
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
