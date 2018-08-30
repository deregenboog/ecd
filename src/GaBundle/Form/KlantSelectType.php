<?php

namespace GaBundle\Form;

use AppBundle\Entity\Klant;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use GaBundle\Entity\Activiteit;
use GaBundle\Entity\Groep;
use GaBundle\Entity\KlantIntake;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'activiteit' => null,
            'groep' => null,
            'placeholder' => '',
            'required' => true,
            'class' => Klant::class,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('klant')->orderBy('klant.achternaam');

                    if ($options['activiteit']) {
                        $this->excludeForActiviteit($options['activiteit'], $builder);
                    }

                    if ($options['groep']) {
                        $this->excludeForGroep($options['groep'], $builder);
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

    private function excludeForActiviteit(Activiteit $activiteit, QueryBuilder $builder)
    {
        // get clients already participating...
        $klanten = [];
        foreach ($activiteit->getKlantDeelnames() as $deelname) {
            $klanten[$deelname->getKlant()->getId()] = $deelname->getKlant();
        }

        // ...and exclude them from choices
        if (count($klanten)) {
            $builder
                ->innerJoin(KlantIntake::class, 'intake', 'WITH', 'intake.klant = klant')
                ->andWhere('klant NOT IN (:klanten)')
                ->setParameter('klanten', $klanten)
            ;
        }
    }

    private function excludeForGroep(Groep $groep, QueryBuilder $builder)
    {
        // get clients already member...
        $klanten = [];
        foreach ($groep->getKlantLidmaatschappen() as $lidmaatschap) {
            $klanten[$lidmaatschap->getKlant()->getId()] = $lidmaatschap->getKlant();
        }

        // ...and exclude them from choices
        if (count($klanten)) {
            $builder
                ->innerJoin(KlantIntake::class, 'intake', 'WITH', 'intake.klant = klant')
                ->andWhere('klant NOT IN (:klanten)')->setParameter('klanten', $klanten)
            ;
        }
    }
}
