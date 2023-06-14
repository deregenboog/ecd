<?php

namespace GaBundle\Form;

use Doctrine\ORM\QueryBuilder;
use GaBundle\Entity\Activiteit;
use GaBundle\Entity\Deelname;
use GaBundle\Entity\Dossier;
use GaBundle\Entity\Groep;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

abstract class DossierSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return EntityType::class;
    }

    protected function excludeForActiviteit(Activiteit $activiteit, QueryBuilder $builder)
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

    protected function excludeForGroep(Groep $groep, QueryBuilder $builder)
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
