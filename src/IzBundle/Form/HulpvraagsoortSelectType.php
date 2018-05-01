<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use IzBundle\Entity\Hulpvraagsoort;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class HulpvraagsoortSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => '',
            'class' => Hulpvraagsoort::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('hulpvraagsoort')
                    ->where('hulpvraagsoort.actief = true')
                    ->orderBy('hulpvraagsoort.naam')
                ;
            },
            'choice_label' => function (Hulpvraagsoort $hulpvraagsoort) {
                if ($hulpvraagsoort->getToelichting()) {
                    return sprintf('%s (%s)', (string) $hulpvraagsoort, $hulpvraagsoort->getToelichting());
                }

                return (string) $hulpvraagsoort;
            },
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
