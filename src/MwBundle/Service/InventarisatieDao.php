<?php

namespace MwBundle\Service;

use AppBundle\Service\AbstractDao;
use MwBundle\Entity\Inventarisatie;
use InloopBundle\Entity\Locatie;

class InventarisatieDao extends AbstractDao implements InventarisatieDaoInterface
{
    protected $class = Inventarisatie::class;

    public function countInventarisaties(
        \DateTime $startdatum,
        \DateTime $einddatum,
        Locatie $locatie = null
    ) {
        $builder = $this->repository->getLeafsQueryBuilder()
            ->addSelect('COUNT(verslag.id) AS aantal_verslagen')
            ->innerJoin('node.verslaginventarisaties', 'verslaginventarisatie')
            ->innerJoin('verslaginventarisatie.verslag', 'verslag')
            ->where('verslag.datum BETWEEN :startdatum AND :einddatum')
            ->setParameters([
                'startdatum' => $startdatum,
                'einddatum' => $einddatum,
            ])
            ->groupBy('node.id');

        if ($locatie) {
            $builder->andWhere('verslag.locatie = :locatie')
                ->setParameter('locatie', $locatie);
        }

        $leafs = $builder->getQuery()->getResult();

        foreach ($leafs as $i => $leaf) {
            $leafs[$i]['path'] = $this->repository->getPath($leaf[0]);
        }

        return $leafs;
    }
}
