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
            'klant' => null,
            'vrijwilliger' => null,
            'placeholder' => '',
            'required' => true,
            'class' => Groep::class,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('groep')->orderBy('groep.naam')
                        ->where('groep.einddatum IS NULL');

                    if ($options['klant']) {
                        // get groups already member of...
                        $klantGroepen = $options['em']->getRepository(Groep::class)
                            ->createQueryBuilder('groep')
                            ->innerJoin('groep.klantlidmaatschappen', 'lidmaatschap')
                            ->innerJoin('lidmaatschap.klant', 'klant', 'WITH', 'klant = :klant')
                            ->setParameter('klant', $options['klant'])
                            ->getQuery()
                            ->getResult();

                        // ...and exclude them from choices
                        if (count($klantGroepen)) {
                            $builder->andWhere('groep NOT IN (:klantGroepen)')->setParameter('klantGroepen', $klantGroepen);
                        }
                    }

                    if ($options['vrijwilliger']) {
                        // get groups already member of...
                        $vrijwilligerGroepen = $options['em']->getRepository(Groep::class)
                            ->createQueryBuilder('groep')
                            ->innerJoin('groep.vrijwilligerlidmaatschappen', 'lidmaatschap')
                            ->innerJoin('lidmaatschap.vrijwilliger', 'vrijwilliger', 'WITH', 'vrijwilliger = :vrijwilliger')
                            ->setParameter('vrijwilliger', $options['vrijwilliger'])
                            ->getQuery()
                            ->getResult();

                        // ...and exclude them from choices
                        if (count($vrijwilligerGroepen)) {
                            $builder->andWhere('groep NOT IN (:vrijwilligerGroepen)')->setParameter('vrijwilligerGroepen', $vrijwilligerGroepen);
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
