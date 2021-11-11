<?php

namespace MwBundle\Service;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Klant;
use AppBundle\Exception\AppException;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use InloopBundle\Entity\Intake;
use InloopBundle\Entity\Locatie;
use InloopBundle\Event\Events;
use InloopBundle\Service\LocatieDao;
use InloopBundle\Service\LocatieDaoInterface;
use Knp\Component\Pager\PaginatorInterface;
use MwBundle\Entity\Verslag;
use MwBundle\Exception\MwException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class IntakeDao extends AbstractDao implements IntakeDaoInterface
{
    protected $paginationOptions = [
//        'defaultSortFieldName' => 'verslag.datum',
        'defaultSortDirection' => 'ASC',
        'sortFieldWhitelist' => [
            'intake.intakedatum',
            'klant.id',
            'klant.achternaam',
            'klant.voornaam',
            'werkgebied.naam',
            'geslacht.volledig',
            'intakelocatie.naam',
//            'laatsteIntake.intakedatum',
            'laatsteIntake.intakedatum',
            'eersteIntake.intakelocatie.naam',
            'intake.created',
            'verslag.datum'
        ],
        'wrap-queries'=>true
    ];

    protected $class = Intake::class;

    protected $alias = 'intake';

    protected $wachtlijstLocaties = array();

    protected $actualWachtlijstLocaties = array();
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        EntityManager $entityManager,
        PaginatorInterface $paginator,
        $itemsPerPage,
        EventDispatcherInterface $eventDispatcher,
        LocatieDaoInterface $locatieDao

    ) {
        $wachtlijstlocaties = $locatieDao->getWachtlijstLocaties();

        $this->wachtlijstLocaties = $wachtlijstlocaties;
        parent::__construct($entityManager, $paginator, $itemsPerPage);
        $this->eventDispatcher = $eventDispatcher;
    }

    public function findAll($page = null, FilterInterface $filter = null)
    {
        //volgende keer mee verder.

//        if(null==$filter || $filter->) return; //geen wachtlijst gelecteerd
//       if(null==$filter || !$filter->locatie) throw new AppException("asdf");


        if($filter && $filter->locatie)
        {
            switch($filter->locatie->getNaam())
            {
                case 'Wachtlijst Economisch Daklozen':
                    $this->actualWachtlijstLocaties = ['Wachtlijst Economisch Daklozen'];
                    $this->paginationOptions['defaultSortFieldName'] = 'verslag.datum';
                    $builder = $this->getEconomischDaklozen();
                    break;
                case 'Wachtlijst T6':
                    $this->actualWachtlijstLocaties = ['Wachtlijst T6'];
                    $builder = $this->getT6();
                    break;

            }
        }
        else
        {
            $builder = $this->getDefault();
        }



//        $builder = $this->repository->createQueryBuilder("verslag");
        if(null !== $filter) $filter->applyTo($builder);
//        $q = $builder->getQuery();
//        $sql = SqlExtractor::getFullSQL($q);


        return parent::doFindAll($builder, $page, $filter);

    }

    private function getDefault()
    {
        $builder = $this->repository->createQueryBuilder("klant")
            ->resetDQLPart("from")
            ->from(Klant::class,"klant")
            ->select("klant,intake,werkgebied,verslag")
            ->leftJoin("klant.geslacht","geslacht")
            ->leftJoin("klant.eersteIntake","intake")
            ->leftJoin("klant.laatsteIntake","laatsteIntake")

            ->leftJoin("intake.intakelocatie","intakelocatie")
            ->leftJoin('klant.werkgebied','werkgebied')
            ->leftJoin("klant.verslagen","verslag")
//            ->leftJoin("verslag.locatie","verslaglocatie")
            ->where("intakelocatie.naam IN (:wachtlijstLocaties)")
//            ->orWhere("verslag.id IN(:verslagIds) AND intake.id IS NULL")
            ->setParameter("wachtlijstLocaties",$this->actualWachtlijstLocaties)
//            ->setParameter("verslagIds",$vIds)
            ->groupBy("klant.id")
//            ->orderBy("verslag.datum","DESC")
        ;
//       if(null !== $filter) $filter->applyTo($builder);
//        $q = $builder->getQuery();
//        $sql = SqlExtractor::getFullSQL($q);
        return $builder;
    }

    private function getEconomischDaklozen()
    {

        //poging 4 :(
//SELECT v2.id, v2.klant_id, MIN(v2.datum), v2.locatie_id FROM
//    verslagen AS v2
//INNER JOIN klanten AS k ON k.id = v2.klant_id
//WHERE v2.locatie_id IN (50)
//    AND k.first_intake_id IS NULL
//GROUP BY v2.klant_id
//ORDER BY v2.klant_id ASC
        $b4 = $this->repository->createQueryBuilder("verslag")
            ->resetDQLParts(["from",'select'])
            ->select(' verslag, klant, locatie')
            ->addSelect('MIN(verslag.datum) AS HIDDEN minDatum')
            ->from(Verslag::class,'verslag')
            ->join('verslag.klant','klant')
            ->join('verslag.locatie','locatie')
            ->join('klant.werkgebied','werkgebied')
            ->where('locatie.naam IN (:wachtlijstLocaties)')
            ->andWhere('klant.eersteIntake IS NULL')
            ->groupBy('klant')
            ->setParameter(':wachtlijstLocaties',$this->actualWachtlijstLocaties)
        ;



        $sql = $b4->getQuery()->getSQL();
        return $b4;
    }

    private function getT6()
    {



        $builder = $this->getDefault();
        return $builder;


    }

    /**
     * @param Intake $entity
     *
     * @return Intake
     */
    public function create(Intake $entity)
    {
        parent::doCreate($entity);

        $this->eventDispatcher->dispatch(Events::INTAKE_CREATED, new GenericEvent($entity));

        return $entity;
    }

    /**
     * @param Intake $entity
     *
     * @return Intake
     */
    public function update(Intake $entity)
    {
        parent::doUpdate($entity);

        $this->eventDispatcher->dispatch(Events::INTAKE_UPDATED, new GenericEvent($entity));

        return $entity;
    }
}
