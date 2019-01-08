<?php

namespace MwBundle\Service;

use AppBundle\Service\AbstractDao;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use InloopBundle\Entity\Locatie;
use MwBundle\Entity\Inventarisatie;

class InventarisatieDao extends AbstractDao implements InventarisatieDaoInterface
{
    /**
     * @var NestedTreeRepository
     */
    protected $repository;

    protected $class = Inventarisatie::class;

    public function findAllAsTree()
    {
        $data = [];
        $roots = $this->repository->getRootNodes('order');
        foreach ($roots as $root) {
            $nodes = $this->repository->getNodesHierarchyQueryBuilder($root)->getQuery()->getResult();
            $data[$root->getId()] = array_merge($nodes, ['rootName' => $root->getTitel()]);
        }

        return $data;
    }

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
