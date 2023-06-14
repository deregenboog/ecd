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
    public function getParent(): ?string
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
            'placeholder' => 'Alle locaties',
            'required' => false,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('locatie')
                        ->where('locatie.datumVan <= DATE(CURRENT_TIMESTAMP())')
                        ->andWhere('locatie.datumTot IS NULL OR locatie.datumTot >= DATE(CURRENT_TIMESTAMP())')
                        ->orderBy('locatie.naam')
                    ;

                    if ($options['gebruikersruimte']) {
                        $builder->andWhere('locatie.gebruikersruimte = true');
                    }
                    if ($options['locatietypes']) {
                        $builder
                            ->leftJoin('locatie.locatieTypes','locatieTypes')
                            ->andWhere('locatieTypes.naam IN (:locatietypes)')
                            ->setParameter('locatietypes',$options['locatietypes'])
                        ;

                    }

                    return $builder;
                };
            },
            'gebruikersruimte' => false,
            'locatietypes'=>[],
        ]);
    }
}
