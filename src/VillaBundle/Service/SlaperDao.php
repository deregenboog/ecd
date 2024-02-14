<?php

namespace VillaBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use VillaBundle\Entity\Slaper;

class SlaperDao extends AbstractDao implements SlaperDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'appKlant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'appKlant.id',
            'appKlant.achternaam',
        ],
//        'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = Slaper::class;

    protected $alias = 'slaper';
    protected $searchEntityName = 'appKlant';


    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, appKlant")
            ->innerJoin('slaper.appKlant', 'appKlant')
            ->leftJoin('appKlant.werkgebied', 'werkgebied')
        ;

        if ($filter) {
            if ($filter->klant) {
                $filter->klant->alias = 'appKlant';
            }
            $filter->applyTo($builder);
        }

        if ($page <= 0) {
            return $builder->getQuery()->getResult();
        }

        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
    }

    /**
     * {inheritdoc}.
     */
    public function dfindAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, klant, aanmelding, afsluiting, verwijzingAanmelding, verwijzingAfsluiting, dossierStatus, deelname, training")
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->leftJoin("{$this->alias}.aanmelding", 'aanmelding')
            ->leftJoin("{$this->alias}.afsluiting", 'afsluiting')
            ->leftJoin('aanmelding.verwijzing', 'verwijzingAanmelding')
            ->leftJoin('afsluiting.verwijzing', 'verwijzingAfsluiting')
            ->leftJoin("{$this->alias}.dossierStatus", 'dossierStatus')
            ->leftJoin("{$this->alias}.deelnames", 'deelname')
            ->leftJoin('deelname.training', 'training')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function findWachtlijst($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('slaper')
            ->select('slaper, klant, aanmelding, verwijzingAanmelding, dossierStatus, lidmaatschap, groep')
            ->innerJoin('slaper.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->leftJoin('slaper.aanmelding', 'aanmelding')
            ->leftJoin('aanmelding.verwijzing', 'verwijzingAanmelding')
            ->leftJoin("{$this->alias}.afsluiting", 'afsluiting')
            ->leftJoin('slaper.dossierStatus', 'dossierStatus')
            ->innerJoin('slaper.lidmaatschappen', 'lidmaatschap')
            ->innerJoin('lidmaatschap.groep', 'groep')
            ->where('slaper.afsluiting IS NULL')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, [
                'defaultSortFieldName' => 'klant.achternaam',
                'defaultSortDirection' => 'asc',
                'sortFieldWhitelist' => [
                    'klant.id',
                    'klant.achternaam',
                    'werkgebied.naam',
                    'groep.naam',
                    'aanmelding.datum',
                    'afsluiting.datum',
                ],
                'wrap-queries' => true, // because of HAVING clause in filter
            ]);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * @param Klant $klant
     *
     * @return Slaper
     */
    public function findOneByKlant(Klant $klant)
    {
        return $this->repository->findOneBy(['klant' => $klant]);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Slaper $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Slaper $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Slaper $entity)
    {
        $this->doDelete($entity);
    }
}
