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
        ],
        'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = Deelnemer::class;

    protected $alias = 'deelnemer';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null): PaginationInterface
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin($this->alias.'.klant', 'klant')
        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    /**
     * {inheritdoc}.
     */
    public function findByMedewerker(Medewerker $medewerker, $page = null, FilterInterface $filter = null): PaginationInterface
    {

        return parent::findAll($page, $filter);
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
     * @param string $name
     *
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
}
