<?php

namespace GaBundle\Form;

use Doctrine\ORM\EntityRepository;
use GaBundle\Entity\Groep;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroepSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => '',
            'required' => true,
            'dossier' => null,
            'class' => Groep::class,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('groep')
                        ->orderBy('groep.naam');

                    if (is_array($options['dossier']) && count($options['dossier']) > 0) {
                        $groepen = array_filter(array_map(function ($lidmaatschap) {
                            return $lidmaatschap->getGroep();
                        }, $options['dossier']->getLidmaatschappen()->toArray()));
                        if (count($groepen)) {
                            $builder->andWhere('groep NOT IN (:groepen)')->setParameter(':groepen', $groepen);
                        }
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
