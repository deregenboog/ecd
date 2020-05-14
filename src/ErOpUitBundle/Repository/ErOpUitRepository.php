<?php
namespace ErOpUitBundle\Repository;

use AppBundle\Repository\DoelstellingRepositoryInterface;
use AppBundle\Repository\DoelstellingRepositoryTrait;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Doelstelling;

class ErOpUitRepository extends EntityRepository implements DoelstellingRepositoryInterface
{
    use DoelstellingRepositoryTrait;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

//    public function __construct(EntityManagerInterface $entityManager)
//    {
//        $this->entityManager = $entityManager;
//    }

    public function getCategory():string
    {
        return DoelstellingRepositoryInterface::CAT_ACTIVERING;
    }


    /**
     * @return int
     */
    public function ErOpUit(Doelstelling $doelstelling,$startdatum = null, $einddatum = null)
    {
       $builder = $this->createQueryBuilder("klant")
           ->select("COUNT(klant.id) as number")
            ->where('klant.inschrijfdatum >= :begin_date')
            ->andWhere('klant.uitschrijfdatum IS NULL OR klant.uitschrijfdatum < :end_date')
           // ->setParameter('date', $date ?: new \DateTime())
//           ->groupBy("klant.id")
           ->setParameter('begin_date',$startdatum)
           ->setParameter('end_date',$einddatum)
        ;
        $r = $builder->getQuery()->getSingleResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
        return $r;
    }

    public function Buurtrestaurants(Doelstelling $doelstelling)
    {
        return 1;
    }
}