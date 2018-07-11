<?php

namespace GaBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Vrijwilliger;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use GaBundle\Entity\Activiteit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviteitSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $today = new \DateTime('today');

        $resolver->setDefaults([
            'klant' => null,
            'vrijwilliger' => null,
            'placeholder' => '',
            'required' => true,
            'class' => Activiteit::class,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('activiteit')
                        ->where('activiteit.datum >= :datum')
                        ->setParameter('datum', new \DateTime('1 january last year'))
                        ->orderBy('activiteit.datum, activiteit.naam')
                    ;

                    if ($options['klant']) {
                        $this->excludeForKlant($options['klant'], $builder);
                    }

                    if ($options['vrijwilliger']) {
                        $this->excludeForVrijwilliger($options['vrijwilliger'], $builder);
                    }

                    return $builder;
                };
            },
            'preferred_choices' => function (Activiteit $activiteit) use ($today) {
                return abs($today->diff($activiteit->getDatum())->days) <= 31;
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

    private function excludeForKlant(Klant $klant, QueryBuilder $builder)
    {
        // get activities already enrolled in...
        $klantActiviteiten = $builder->getEntityManager()->getRepository(Activiteit::class)
            ->createQueryBuilder('activiteit')
            ->innerJoin('activiteit.klantDeelnames', 'deelname')
            ->innerJoin('deelname.klant', 'klant', 'WITH', 'klant = :klant')
            ->setParameter('klant', $klant)
            ->getQuery()
            ->getResult()
        ;

        // ...and exclude them from choices
        if (count($klantActiviteiten)) {
            $builder->andWhere('activiteit NOT IN (:klantActiviteiten)')->setParameter('klantActiviteiten', $klantActiviteiten);
        }
    }

    private function excludeForVrijwilliger(Vrijwilliger $vrijwilliger, QueryBuilder $builder)
    {
        // get activities already enrolled in...
        $vrijwilligerActiviteiten = $builder->getEntityManager()->getRepository(Activiteit::class)
            ->createQueryBuilder('activiteit')
            ->innerJoin('activiteit.vrijwilligerDeelnames', 'deelname')
            ->innerJoin('deelname.vrijwilliger', 'vrijwilliger', 'WITH', 'vrijwilliger = :vrijwilliger')
            ->setParameter('vrijwilliger', $vrijwilliger)
            ->getQuery()
            ->getResult()
        ;

        // ...and exclude them from choices
        if (count($vrijwilligerActiviteiten)) {
            $builder
                ->andWhere('activiteit NOT IN (:vrijwilligerActiviteiten)')
                ->setParameter('vrijwilligerActiviteiten', $vrijwilligerActiviteiten)
            ;
        }
    }
}
