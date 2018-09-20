<?php

namespace InloopBundle\Form;

use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\Locatie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocatieSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Locatie::class,
            'placeholder' => '',
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('locatie')
                        ->where("locatie.datumVan <> '0000-00-00' AND locatie.datumVan <= NOW()")
                        ->andWhere("locatie.datumTot IS NULL OR locatie.datumTot = '0000-00-00' OR locatie.datumTot >= NOW()")
                        ->orderBy('locatie.naam')
                    ;

                    if ($options['gebruikersruimte']) {
                        $builder->andWhere('locatie.gebruikersruimte = true');
                    }

                    return $builder;
                };
            },
            'gebruikersruimte' => false,
        ]);
    }
}
