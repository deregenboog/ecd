<?php

namespace UhkBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Knp\Component\Pager\Pagination\PaginationInterface;
use UhkBundle\Entity\Deelnemer;
use UhkBundle\Entity\Document;

class DeelnemerDao extends AbstractDao implements DeelnemerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.voornaam',
            'klant.achternaam',
            'deelnemer.type',
            'projecten.naam',
        ],
        'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = Deelnemer::class;
    protected $alias = 'deelnemer';
    protected $searchEntityName = 'klant';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, ?FilterInterface $filter = null): PaginationInterface
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin($this->alias.'.klant', 'klant')
            ->leftJoin($this->alias.'.verslagen', 'verslag')
            ->leftJoin($this->alias.'.verslagen', 'v2', 'WITH', 'verslag.datum < v2.datum OR (verslag.datum = v2.datum AND verslag.id < v2.id)')
            ->leftJoin($this->alias.'.projecten', 'projecten')
            ->leftJoin($this->alias.'.projecten', 'v3', 'WITH', 'projecten.id = v3.id')

        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    /**
     * {inheritdoc}.
     */
    public function findByMedewerker(Medewerker $medewerker, $page = null, ?FilterInterface $filter = null): PaginationInterface
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin($this->alias.'.klant', 'klant')
            ->innerJoin($this->alias.'.medewerker', 'medewerker')
            ->where('medewerker = :medewerker')
            ->setParameter('medewerker', $medewerker)

        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    /**
     * {inheritdoc}.
     */
    public function findOneByKlant(Klant $klant)
    {
        return $this->repository->findOneBy(['klant' => $klant]);
    }

    /**
     * {inheritdoc}.
     */
    public function findOneByDocument(Document $document)
    {
        return $this->repository->createQueryBuilder($this->alias)
            ->innerJoin($this->alias.'.documenten', 'document', 'WITH', 'document = :document')
            ->setParameter('document', $document)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    /**
     * @return Deelnemer
     */
    public function findOneByName(string $name)
    {
        return $this->repository->createQueryBuilder($this->alias)
            ->innerJoin($this->alias.'.klant', 'klant')
            ->where("REPLACE(CONCAT_WS('', klant.voornaam, klant.tussenvoegsel, klant.achternaam), ' ', '') = :naam")
            ->setParameter('naam', preg_replace('/\s/', '', $name))
            ->getQuery()
            ->getSingleResult()
        ;
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

    /**
     * {inheritdoc}.
     */
    public function countByProjectId(int $projectId): int {
        $qb = $this->repository->createQueryBuilder($this->alias)
            ->select('COUNT(deelnemer.id)')
            ->innerJoin($this->alias.'.projecten', 'projecten')
            ->where('projecten.id = :projectId')
            ->setParameter('projectId', $projectId);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
