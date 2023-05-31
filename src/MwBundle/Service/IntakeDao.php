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
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\MwDossierStatus;
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
        $w =[];
        foreach($wachtlijstlocaties as $veld=>$wachtlijst)
        {
            $w[] = $wachtlijst['naam'];
        }
        $this->wachtlijstLocaties = $w;

        parent::__construct($entityManager, $paginator, $itemsPerPage);
        $this->eventDispatcher = $eventDispatcher;
    }

    public function findAll($page = null, FilterInterface $filter = null)
    {
        if($filter && $filter->locatie)
        {
//            $i = array_values($this->wachtlijstLocaties);
//            $k = array_search('Wachtlijst Economisch Daklozen',$i);
//            $deleted =array_splice($this->wachtlijstLocaties,$k,1);
            switch($filter->locatie->getWachtlijst())
            {
                case '2':
//                    $this->actualWachtlijstLocaties = $deleted;
                    $this->paginationOptions['defaultSortFieldName'] = 'verslag.datum';
                    $builder = $this->getEconomischDaklozen();
                    break;
                default:
//                    $this->actualWachtlijstLocaties = $this->wachtlijstLocaties;
                    $builder = $this->getT6();
                    break;
            }
        }
        else
        {
            $builder = $this->getDefault();
        }

        if(null !== $filter) $filter->applyTo($builder);
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
            ->where("intakelocatie.wachtlijst = :wachtlijsttype")
//            ->orWhere("verslag.id IN(:verslagIds) AND intake.id IS NULL")
            ->setParameter("wachtlijsttype",1)
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
        $b4 = $this->repository->createQueryBuilder("verslag");
        $b4
            ->resetDQLParts(["from",'select'])
            ->select(' verslag, klant, locatie')
            ->addSelect('MIN(verslag.datum) AS HIDDEN minDatum')
            ->from(Verslag::class,'verslag')
            ->join('verslag.klant','klant')
            ->join('verslag.locatie','locatie')
            ->join('klant.huidigeMwStatus', 'huidigeMwStatus')
            ->leftJoin('klant.werkgebied','werkgebied')
            ->where('locatie.wachtlijst = :wachtlijsttype')
            ->andWhere($b4->expr()->isInstanceOf('huidigeMwStatus', Aanmelding::class))
            ->groupBy('klant.id')
            ->setParameter(':wachtlijsttype',2)
//            ->setParameter(':mwAanmelding',$this->entityManager->getClassMetadata(Aanmelding::class))
        ;

//        $ql = $b4->getDQL();
//        $sql = $b4->getQuery()->getSQL();
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

        $this->eventDispatcher->dispatch(new GenericEvent($entity), Events::INTAKE_CREATED);

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

        $this->eventDispatcher->dispatch(new GenericEvent($entity), Events::INTAKE_UPDATED);

        return $entity;
    }
}
