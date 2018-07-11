<?php

namespace GaBundle\Form;

use Doctrine\ORM\EntityRepository;
use GaBundle\Entity\Klantdossier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantdossierSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Deelnemer',
            'placeholder' => '',
            'required' => true,
            'class' => Klantdossier::class,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('dossier')
                        ->innerJoin('dossier.klant', 'klant')
                        ->orderBy('klant.achternaam');

                    if (count($options['include'])) {
                        $builder->where('dossier IN (:include)')->setParameter('include', $options['include']);
                    }

                    if (count($options['exclude'])) {
                        $builder->where('dossier NOT IN (:exclude)')->setParameter('exclude', $options['exclude']);
                    }

                    return $builder;
                };
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }
}
