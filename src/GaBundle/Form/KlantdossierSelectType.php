<?php

namespace GaBundle\Form;

use AppBundle\Service\NameFormatter;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use GaBundle\Entity\Activiteit;
use GaBundle\Entity\Deelname;
use GaBundle\Entity\Dossier;
use GaBundle\Entity\Groep;
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
            'activiteit' => null,
            'groep' => null,
            'label' => 'Deelnemer',
            'placeholder' => '',
            'required' => true,
            'class' => Klantdossier::class,
            'choice_label' => function (Klantdossier $dossier) {
                return NameFormatter::formatFormal($dossier->getKlant());
            },
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('dossier')
                        ->innerJoin('dossier.klant', 'klant')
                        ->orderBy('klant.achternaam');

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
        $map = function (Deelname $deelname) {
            if ($deelname->getDossier()) {
                return $deelname->getDossier()->getId();
            }
        };

        $dossierIds = array_map($map, $activiteit->getDeelnames()->toArray());
        $dossierIds = array_filter($dossierIds);

        if (count($dossierIds) > 0) {
            $builder->andWhere('dossier.id NOT IN (:dossier_ids)')
                ->setParameter('dossier_ids', $dossierIds);
        }
    }

    private function excludeForGroep(Groep $groep, QueryBuilder $builder)
    {
        $map = function (Dossier $dossier) {
            return $dossier->getId();
        };

        $dossierIds = array_map($map, $groep->getDossiers()->toArray());
        $dossierIds = array_filter($dossierIds);

        if (count($dossierIds)) {
            $builder->andWhere('dossier.id NOT IN (:dossier_ids)')
                ->setParameter('dossier_ids', $dossierIds);
        }
    }
}
