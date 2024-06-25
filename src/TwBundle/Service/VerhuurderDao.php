<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Model\UsesKlantTrait;
use AppBundle\Service\AbstractDao;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\Pagination\PaginationInterface;
use TwBundle\Entity\Verhuurder;

class VerhuurderDao extends AbstractDao implements VerhuurderDaoInterface
{
    use UsesKlantTrait;

    protected $paginationOptions = [
        'defaultSortFieldName' => 'appKlant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'appKlant.id',
            'appKlant.achternaam',
            'werkgebied.naam',
            'verhuurder.aanmelddatum',
            'verhuurder.afsluitdatum',
            'verhuurder.wpi',
            'verhuurder.ksgw',
            'ambulantOndersteuner.achternaam',
            'project.naam'
        ],
//        'wrap-queries'=>true,
    ];

    protected $class = Verhuurder::class;

    protected $alias = 'verhuurder';

    protected $searchEntityName = 'appKlant';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('verhuurder')
            ->innerJoin('verhuurder.appKlant', 'appKlant')
            ->leftJoin('verhuurder.ambulantOndersteuner','ambulantOndersteuner')
            ->leftJoin('appKlant.werkgebied', 'werkgebied')
            ->leftJoin('verhuurder.afsluiting', 'afsluiting')
            ->leftJoin('verhuurder.project','project')
            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
        ;

        /**
         * Op de een of andere manier is het niet goed mogelijk dit via Doctrine te filteren.
         * Het kan vast, maar ik krijg het nu niet voor elkaar.
         * Punt is dat de join op huuraanbiedingen en huurovererenkomsten als cartesiaans product wordt gemaakt.
         * en het er niet omgaat dat er een mogelijke match is in de join, maar dat de meest recente koppeling lopend is of niet.
         * Dus moet je met WINDOW functies gaan werken,
         * of self joins of wat dan ook.
         * En dat is in doctrine niet zo fijn.
         * Dus dan maar zo, na veel te lang geprobeerd te hebben...
         *
         *
         */
        if(null !== $filter->gekoppeld)
        {
            $result = parent::doFindAll($builder,null, $filter);
            $filteredResult = new ArrayCollection();

            foreach($result as $row)
            {
                if($filter->gekoppeld === $row->isGekoppeld())
                {
                    $filteredResult->add($row);
                }
            }
            return $this->paginator->paginate($filteredResult, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return parent::doFindAll($builder,$page, $filter);
    }


    public function create(Verhuurder $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Verhuurder $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Verhuurder $entity)
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
            ->innerJoin("{$this->alias}.huuraanbiedingen", 'huuraanbod')
            ->innerJoin('huuraanbod.huurovereenkomst', 'huurovereenkomst')
            ->andWhere('huurovereenkomst.startdatum BETWEEN :start AND :end')
            ->andWhere('huurovereenkomst.isReservering = 0')
        ;

        return $builder->getQuery()->getResult();
    }

    public function countOntkoppeld(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->innerJoin("{$this->alias}.huuraanbiedingen", 'huuraanbod')
            ->innerJoin('huuraanbod.huurovereenkomst', 'huurovereenkomst')
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
