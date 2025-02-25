<?php

namespace VillaBundle\Service;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Model\DaoHasDossierStatusInterface;
use AppBundle\Model\DaoHasDossierStatusTrait;
use AppBundle\Service\AbstractDao;
use Doctrine\DBAL\Result;
use VillaBundle\Entity\Aanmelding;
use VillaBundle\Entity\DossierStatus;
use VillaBundle\Entity\Slaper;

class SlaperDao extends AbstractDao implements SlaperDaoInterface,DaoHasDossierStatusInterface
{
    use DaoHasDossierStatusTrait;

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
        // First, execute a query to get the maximum id and maximum datum for each slaaper


        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, appKlant")
            ->innerJoin($this->alias.'.appKlant', 'appKlant')
            ->leftJoin('appKlant.werkgebied', 'werkgebied')

        ;
        $this->addDossierStatusToQueryBuilder($builder, $this->alias, 'dossierStatussen', 'slaper', DossierStatus::class);


        if ($filter) {
            if ($filter->klant) {
                $filter->klant->alias = 'appKlant';
            }
            $filter->applyTo($builder);
        }
//
        if ($page <= 0) {
            return $builder->getQuery()->getResult();
        }



        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
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
//        $entity->getDossierStatus()->setSlaper($entity);

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

    /**
     * Count slapers with their overnachtingen grouped by type
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return array
     */
    // In SlaperDao.php

    /**
     * Count slapers with their overnachtingen grouped by type
     */
    public function countOvernachtingenByType(\DateTime $startDate, \DateTime $endDate): array
    {
        $qb = $this->repository->createQueryBuilder('s')
            ->select('
            s.type,
            COUNT(o.id) as aantal
        ')
            ->innerJoin('s.appKlant', 'k')
            ->leftJoin('s.overnachtingen', 'o', 'WITH', 'o.datum BETWEEN :startDate AND :endDate')
            ->groupBy('s.type')
            ->orderBy('s.type', 'ASC')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
        ;

        $results = $qb->getQuery()->getResult();
        return $this->transformSlaperTypes($results);
    }

    /**
     * Count unique slapers grouped by type
     */
    public function countSlapersByType(\DateTime $startDate, \DateTime $endDate): array
    {
        $qb = $this->repository->createQueryBuilder('s')
            ->select('
            s.type,
            COUNT(DISTINCT k.id) as aantal
        ')
            ->innerJoin('s.appKlant', 'k')
            ->innerJoin('s.overnachtingen', 'o', 'WITH', 'o.datum BETWEEN :startDate AND :endDate')
            ->groupBy('s.type')
            ->orderBy('s.type', 'ASC')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
        ;

        $results = $qb->getQuery()->getResult();
        return $this->transformSlaperTypes($results);
    }

    /**
     * Get slapers with their overnachtingen count for a given period
     */
    public function getSlapersWithOvernachtingen(\DateTime $startDate, \DateTime $endDate): array
    {
        $qb = $this->repository->createQueryBuilder('s')
            ->select('
            k.id,
            CONCAT_WS(\' \', k.achternaam, k.tussenvoegsel, k.voornaam) as naam,
            s.type,
            COUNT(o.id) as aantal
        ')
            ->innerJoin('s.appKlant', 'k')
            ->leftJoin('s.overnachtingen', 'o', 'WITH', 'o.datum BETWEEN :startDate AND :endDate')
            ->having('aantal > 0')
            ->groupBy('k.id, k.achternaam, k.voornaam')
            ->orderBy('k.achternaam, k.voornaam', 'ASC')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
        ;

        $results = $qb->getQuery()->getResult();
        return $this->transformSlaperTypes($results);
    }

    /**
     * Transform type IDs to their readable labels in a result set
     */
    private function transformSlaperTypes($results): array
    {
        foreach ($results as $key => $row) {
            $results[$key]['type'] = Slaper::$types[$row['type']] ?? $row['type'];
        }
        return $results;
    }
}
