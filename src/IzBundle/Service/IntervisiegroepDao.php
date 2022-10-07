<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use IzBundle\Entity\Intervisiegroep;

class IntervisiegroepDao extends AbstractDao implements IntervisiegroepDaoInterface
{
    protected $paginationOptions = [
            'defaultSortFieldName' => 'intervisiegroep.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'intervisiegroep.id',
            'intervisiegroep.naam',
            'intervisiegroep.startdatum',
            'intervisiegroep.einddatum',
            'medewerker.voornaam',
        ],
    ];

    protected $class = Intervisiegroep::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('intervisiegroep')
            ->addSelect('medewerker')
            ->leftJoin('intervisiegroep.medewerker', 'medewerker')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function find($id)
    {
        // try to find with correct collection of members
        $entity = $this->repository->createQueryBuilder('intervisiegroep')
            ->addSelect('lidmaatschap, izVrijwilliger, vrijwilliger, medewerker')
            ->innerJoin('intervisiegroep.lidmaatschappen', 'lidmaatschap')
            ->innerJoin('lidmaatschap.vrijwilliger', 'izVrijwilliger', 'WITH', 'izVrijwilliger.afsluitDatum > DATE(\'NOW\') OR izVrijwilliger.afsluitDatum IS NULL')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('intervisiegroep.medewerker', 'medewerker')
            ->where('intervisiegroep.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if ($entity) {
            return $entity;
        }

        // return with empty member collection otherwise
        return parent::find($id);
    }

    public function create(Intervisiegroep $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Intervisiegroep $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Intervisiegroep $entity)
    {
        $this->doDelete($entity);
    }
}
