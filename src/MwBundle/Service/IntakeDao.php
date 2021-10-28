<?php

namespace MwBundle\Service;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Klant;
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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class IntakeDao extends AbstractDao implements IntakeDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'verslag.datum',
        'defaultSortDirection' => 'DESC',
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
        /*
         * Onderstaande query is de basis; maar neit mogelijk in doctrine met QB.
         * Om het niet ingewikkelder te maken dan nodig eerst de losse klant ids ophalen.
         * En dan later de boel maar in elkaar frotten...
         *

        SELECT k.id, ki.klant_id, kv.klant_id FROM klanten k
    LEFT JOIN
    (
        SELECT i.klant_id FROM intakes AS i
                 LEFT JOIN intakes AS i2
                           ON i.klant_id = i2.klant_id
    AND (i.created > i2.created
        OR (i.created = i2.created AND i.id > i2.id))
        WHERE i2.klant_id IS NULL
    AND i.locatie2_id IN (49, 50)
    ) AS ki ON k.id = ki.klant_id
    LEFT JOIN
    (
        SELECT v.klant_id FROM verslagen v
        WHERE v.locatie_id IN (50)
        ) AS kv ON k.id = kv.klant_id
WHERE (ki.klant_id IS NOT NULL OR kv.klant_id IS NOT NULL)
         */

        //niet nodig omdat eerste intake en laatste intake al dmv denormalisatie zijn toegevoegd en dus te verkrijgen zijn
//        $subQuery1 = $this->repository->createQueryBuilder("intake")
//            ->select("intake")
//            ->leftJoin(Intake::class,"i2",Join::WITH,"intake.klant = i2.klant
//            AND intake.created > i2.created
//            OR (intake.created = i2.created AND intake.id > i2.id)")
//            ->where("i2.klant IS NULL")
//            ->andWhere("intake.intakelocatie IN (49,50)")
//            ;
//
////        $sq1 = $subQuery1->getQuery();
////        $sql = SqlExtractor::getFullSQL($sq1);
//        $result = $subQuery1->getQuery()->getResult();
//        foreach($result as $r)
//        {
//            $ikIds[] = $r->getKlant()->getId();
//            $iIds = $r->getId();
//        }


        $subQuery2 = $this->repository->createQueryBuilder("verslag")
            ->resetDQLPart("select")

            ->select("DISTINCT verslag.id")
            ->resetDQLPart("from")
            ->from(Verslag::class,"verslag")
//            ->select("verslag")
            ->leftJoin(Verslag::class,"v2",Join::WITH,"verslag.klant = v2.klant
            AND verslag.created > v2.created
            OR (verslag.created = v2.created AND verslag.id < v2.id) AND v2.klant IS NULL")
            ->leftJoin("verslag.locatie","locatie")
            ->leftJoin("verslag.klant","klant")
            ->leftJoin("klant.werkgebied","werkgebied")
//            ->where("v2.klant IS NULL")
            ->andWhere("locatie.naam IN (:wachtlijstLocaties)")
            ->andWhere("klant.eersteIntake IS NULL")
            ->groupBy("verslag.klant")
            ->setParameter("wachtlijstLocaties",$this->wachtlijstLocaties)
        ;

        if($filter !== null)
        {
//            $filter->applyTo($subQuery2);
        }

//        $sq2 = $subQuery2->getQuery();
//        $sql = SqlExtractor::getFullSQL($subQuery2->getQuery());
        $result = $subQuery2->getQuery()->getResult();

        $vIds = array();
        foreach($result as $r)
        {
            $vIds[] = $r['id'];
        }


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
            ->orWhere("verslag.id IN(:verslagIds) AND intake.id IS NULL")
            ->setParameter("wachtlijstLocaties",$this->wachtlijstLocaties)
            ->setParameter("verslagIds",$vIds)
            ->groupBy("klant.id")
//            ->orderBy("verslag.datum","DESC")



            ;
       if(null !== $filter) $filter->applyTo($builder);
//        $q = $builder->getQuery();
//        $sql = SqlExtractor::getFullSQL($q);

        return parent::doFindAll($builder, $page, $filter);
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
