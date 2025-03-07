<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use TwBundle\Entity\Klant;

class KlantDao extends AbstractDao implements KlantDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'appKlant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'appKlant.achternaam',
            'appKlant.geslacht',
            'appKlant.geboortedatum',
            'klant.intakeStatus',
            'klant.aanmelddatum',
//            'werkgebied.naam',
//            'klant.automatischeIncasso',
//            'klant.inschrijvingWoningnet',
//            'klant.waPolis',
//            'klant.aanmelddatum',
//            'klant.afsluitdatum',
//            'klant.wpi',
//            'medewerker.achternaam',
//            'ambulantOndersteuner.achternaam',
            'intakeStatus.naam',
            'geslacht.volledig',
            'bindingRegio.naam',
            'shortlist.achternaam',
        ],
    ];

    protected $class = Klant::class;

    protected $alias = 'klant';
    protected $searchEntityName = 'appKlant';

    public function __construct(EntityManagerInterface $entityManager, PaginatorInterface $paginator, $itemsPerPage)
    {
        parent::__construct($entityManager, $paginator, $itemsPerPage);

        $this->entityManager->getFilters()->disable('zichtbaar');
    }

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('klant')
            ->innerJoin('klant.appKlant', 'appKlant')
            ->leftJoin('appKlant.geslacht', 'geslacht')
            ->leftJoin('klant.intakeStatus', 'intakeStatus')
            ->leftJoin('klant.bindingRegio', 'bindingRegio')
            ->leftJoin('klant.shortlist', 'shortlist')
            ->innerJoin('klant.projecten', 'project')
            ->leftJoin('klant.ambulantOndersteuner', 'ambulantOndersteuner')
            ->leftJoin('appKlant.werkgebied', 'werkgebied')
            ->leftJoin('klant.afsluiting', 'afsluiting')
            ->leftJoin('klant.medewerker', 'medewerker')
            ->andWhere('(afsluiting.tonen IS NULL OR afsluiting.tonen = true)')
        ;
        //        $builder = $this->repository->createQueryBuilder($this->alias)
        //            ->innerJoin($this->alias.'.klant', 'klant')
        //        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    public function create(Klant $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Klant $entity)
    {
        // update huisgenoot ook, maar werkt niet want bij unsetten gaat het fout
        // computeChangeSet op UnitOfWork werkt niet, zie https://stackoverflow.com/questions/9057558/is-there-a-built-in-way-to-get-all-of-the-changed-updated-fields-in-a-doctrine-2
        //        $updatedEntities = $this->entityManager->getUnitOfWork()->getScheduledEntityUpdates();

        $this->doUpdate($entity);
    }

    public function delete(Klant $entity)
    {
        $this->doDelete($entity);
    }

    public function countAangemeld(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->andWhere("{$this->alias}.aanmelddatum BETWEEN :start AND :end")
        ;

        return $builder->getQuery()->getResult();
    }

    public function countGekoppeld(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->innerJoin("{$this->alias}.huurverzoeken", 'huurverzoek')
            ->innerJoin('huurverzoek.huurovereenkomst', 'huurovereenkomst')
            ->andWhere('huurovereenkomst.startdatum BETWEEN :start AND :end')
            ->andWhere('huurovereenkomst.isReservering = 0')
        ;

        return $builder->getQuery()->getResult();
    }

    public function countOntkoppeld(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->innerJoin("{$this->alias}.huurverzoeken", 'huurverzoek')
            ->innerJoin('huurverzoek.huurovereenkomst', 'huurovereenkomst')
            ->andWhere('huurovereenkomst.einddatum BETWEEN :start AND :end')
            ->andWhere('huurovereenkomst.isReservering = 0')
        ;

        return $builder->getQuery()->getResult();
    }

    public function countAfgesloten(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->andWhere("{$this->alias}.afsluitdatum BETWEEN :start AND :end")
        ;

        return $builder->getQuery()->getResult();
    }

    private function getCountBuilder(\DateTime $startdate, \DateTime $enddate)
    {
        return $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal")
            ->leftJoin("{$this->alias}.afsluiting", 'afsluiting')
            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
            ->setParameters(['start' => $startdate, 'end' => $enddate])
        ;
    }
}
