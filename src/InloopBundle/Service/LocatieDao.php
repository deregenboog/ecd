<?php

namespace InloopBundle\Service;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Event\Events;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class LocatieDao extends AbstractDao implements LocatieDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'locatie.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'locatie.id',
            'locatie.naam',
            'locatie.actief',
            'locatie.wachtlijst',
        ],
    ];

    protected $class = Locatie::class;

    protected $alias = 'locatie';

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        EntityManager $entityManager,
        PaginatorInterface $paginator,
        $itemsPerPage,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($entityManager, $paginator, $itemsPerPage);
        $this->eventDispatcher = $eventDispatcher;
    }

    public function findAllActiveLocations()
    {
        $builder = $this->repository->createQueryBuilder($this->alias);
        $builder->where("(locatie.datumTot IS NULL OR locatie.datumTot > :now)")
                ->andWhere("locatie.datumVan <= :now")
                ->setParameter(":now", (new \DateTime('now'))->format("Y-m-d"))
            ;
        return $this->doFindAll($builder,null);
    }
    public function findAllActiveLocationsOfTypes(array $types,$mode = "OR")
    {
        $builder = $this->repository->createQueryBuilder($this->alias);
        $builder->innerJoin("locatie.locatieTypes","locatieTypes")
            ->where("(locatie.datumTot IS NULL OR locatie.datumTot > :now)")
            ->andWhere("locatie.datumVan <= :now")
            ->andWhere("locatieTypes.naam IN (:locatietypes)")

            ->setParameter("now", (new \DateTime('now'))->format("Y-m-d"))
            ->setParameter("locatietypes",$types)
        ;
        //['Inloop']

        $sql = SqlExtractor::getFullSQL($builder->getQuery());
        return $this->doFindAll($builder,null);
    }
    public function findAllActiveLocationsOfTypeInloop()
    {
        $builder = $this->repository->createQueryBuilder($this->alias);
        $builder->innerJoin("locatie.locatieTypes","locatieTypes")
            ->where("(locatie.datumTot IS NULL OR locatie.datumTot > :now)")
            ->andWhere("locatie.datumVan <= :now")
            ->andWhere("locatieTypes.naam IN (:locatietypes)")

            ->setParameter("now", (new \DateTime('now'))->format("Y-m-d"))
            ->setParameter("locatietypes",['Inloop'])
        ;
//        $sql = SqlExtractor::getFullSQL($builder->getQuery());
        return $this->doFindAll($builder,null);
    }

    public function findAllActiveLocationsOfTypeMW()
    {
        $builder = $this->repository->createQueryBuilder($this->alias);
        $builder->innerJoin("locatie.locatieTypes","locatieTypes")
            ->where("(locatie.datumTot IS NULL OR locatie.datumTot > :now)")
            ->andWhere("locatie.datumVan <= :now")
            ->andWhere("locatieTypes.naam IN (:locatietypes)")

            ->setParameter("now", (new \DateTime('now'))->format("Y-m-d"))
            ->setParameter("locatietypes",['Maatschappelijk werk'])
        ;
//        $sql = SqlExtractor::getFullSQL($builder->getQuery());
        return $this->doFindAll($builder,null);
    }

    public function create(Locatie $locatie)
    {
        $this->doCreate($locatie);
        $this->eventDispatcher->dispatch(new GenericEvent($locatie), Events::LOCATIE_CHANGED);
    }

    public function update(Locatie $locatie)
    {
        $this->doUpdate($locatie);
        $this->eventDispatcher->dispatch(new GenericEvent($locatie), Events::LOCATIE_CHANGED);
    }

    public function delete(Locatie $locatie)
    {
        $this->doDelete($locatie);
        $this->eventDispatcher->dispatch(new GenericEvent($locatie), Events::LOCATIE_CHANGED);
    }

    public function getWachtlijstLocaties()
    {
        $builder = $this->entityManager->createQueryBuilder("locatie");
        $builder->select("locatie.naam")
            ->from(Locatie::class,"locatie")
            ->innerJoin('locatie.locatieTypes','locatietypes')
            ->where("locatie.wachtlijst > 0")
            ->andWhere('locatietypes.naam IN (:locatietypes)')
            ->setParameter('locatietypes',['Wachtlijst']);
        ;
        $wachtlijstlocaties = $builder->getQuery()->getResult();
        return $wachtlijstlocaties;
    }
}
