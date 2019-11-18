<?php

namespace OekBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use OekBundle\Entity\Deelnemer;

class DeelnemerDao extends AbstractDao implements DeelnemerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'werkgebied.naam',
            'training.naam',
            'aanmelding.datum',
            'afsluiting.datum',
        ],
        'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = Deelnemer::class;

    protected $alias = 'deelnemer';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
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
        $builder = $this->repository->createQueryBuilder('deelnemer')
            ->select('deelnemer, klant, aanmelding, verwijzingAanmelding, dossierStatus, lidmaatschap, groep')
            ->innerJoin('deelnemer.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->leftJoin('deelnemer.aanmelding', 'aanmelding')
            ->leftJoin('aanmelding.verwijzing', 'verwijzingAanmelding')
            ->leftJoin("{$this->alias}.afsluiting", 'afsluiting')
            ->leftJoin('deelnemer.dossierStatus', 'dossierStatus')
            ->innerJoin('deelnemer.lidmaatschappen', 'lidmaatschap')
            ->innerJoin('lidmaatschap.groep', 'groep')
            ->where('deelnemer.afsluiting IS NULL')
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
     * @return Deelnemer
     */
    public function findOneByKlant(Klant $klant)
    {
        return $this->repository->findOneBy(['klant' => $klant]);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Deelnemer $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Deelnemer $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Deelnemer $entity)
    {
        $this->doDelete($entity);
    }
}
