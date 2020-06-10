<?php

namespace MwBundle\Repository;

use AppBundle\Repository\DoelstellingRepositoryInterface;
use AppBundle\Repository\DoelstellingRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Metadata\ClassMetadata;
use MwBundle\Entity\Info;
use MwBundle\Entity\Verslag;

class KlantInfoRepository extends EntityRepository implements DoelstellingRepositoryInterface
{
    use DoelstellingRepositoryTrait;

    public $economischDaklozenLocaties;

    public function setEconomischDaklozenLocaties($locaties)
    {
        $this->economischDaklozenLocaties = $locaties;
    }

    public function getCategory(): string
    {
        return DoelstellingRepositoryInterface::CAT_HULPVERLENING;
    }

    public function initDoelstellingcijfers(): void
    {
        $economischDaklozenLocatiesStr = implode(", ",$this->economischDaklozenLocaties);
        $this->addDoelstellingcijfer(
            "Aantal clienten dat een verslag heeft dat is vastgelegd in de periode, die een RIS beschikking heeft die nog loopt in de periode, vanaf de locaties '$economischDaklozenLocatiesStr'.",
            "4160",
            "Ambulante ondersteuning",
            function($doelstelling,$startdatum,$einddatum){
                $r = $this->getAantalRisClientenLocaties($startdatum,$einddatum,$this->economischDaklozenLocaties);
                return $r;
            }
        );

    }


    private function getAantalRisClientenLocaties($startdatum,$einddatum,$locaties)
    {

        $builder = $this->createQueryBuilder("info");


        $builder->select("COUNT(DISTINCT klant.id) AS aantalKlanten")
            ->innerJoin("info.klant","klant")
            ->innerJoin("klant.verslagen","verslag")
            ->innerJoin("verslag.locatie","locatie")
            ->andWhere("info.risDatumTot IS NOT NULL")
            ->andWhere("DATE(info.risDatumTot) > :startdatum")
            ->andWhere("verslag.type = :mw_verslagType")
            ->andWhere("DATE(verslag.datum) BETWEEN :startdatum AND :einddatum")
            ->andWhere("locatie.naam IN (:locatieNamen)")
            ->setParameter("mw_verslagType",Verslag::TYPE_MW)
            ->setParameter("startdatum",$startdatum)
            ->setParameter("einddatum",$einddatum)
            ->setParameter("locatieNamen",$locaties)
        ;

        $query = $builder->getQuery();
        $sql = $query->getSQL();
        $parameters = $query->getParameters();
        $result = $query->getSingleScalarResult();
        return $result;
    }
}