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

class KlantRepository extends EntityRepository implements DoelstellingRepositoryInterface
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
            "Aantal clienten dat een verslag heeft dat is vastgelegd in de periode, vanaf de locatie 'zonder zorg'.",
            "4155",
            "Zonder zorg",
            function($doelstelling,$startdatum,$einddatum){
                $r = $this->getAantalClientenLocatie($startdatum,$einddatum,["Zonder Zorg"]);
                return $r;
            }
        );

        $this->addDoelstellingcijfer(
            "Aantal clienten dat een verslag heeft dat is vastgelegd in de periode, vanaf de locaties '$economischDaklozenLocatiesStr'.",
            "4152",
            "Economisch daklozen",
            function($doelstelling,$startdatum,$einddatum){
                $r = $this->getAantalClientenLocatie($startdatum,$einddatum,$this->economischDaklozenLocaties);
                return $r;
            }
        );
        $losseLocaties = [
            "6550"=>'De Meeuw',
            "6551"=>'Claverhuis',
        ];

        foreach ($losseLocaties as $kpl=>$locatie) {
            $locatieArr = [$locatie];
            $this->addDoelstellingcijfer(
                "Aantal klanten op locatie $locatie dat in afgelopen periode een (nieuwe) waarvan de laatste intake datum binnen periode valt.",
                $kpl,
                $locatie,
                function($doelstelling,$startdatum,$einddatum) use ($locatieArr) {
                    $r = $this->getAantalClientenMetNieuweIntakeVoorLocaties($startdatum,$einddatum,$locatieArr);
                    return $r;
                }
            );
        }



    }
    private function getAantalClientenMetNieuweIntakeVoorLocaties($startdatum,$einddatum,$locaties)
    {
        $builder = $this->createQueryBuilder("klant");
        $builder->select("COUNT(DISTINCT klant.id) AS aantalKlanten")
//            ->innerJoin("klant.verslagen","verslag")
            ->innerJoin("klant.laatsteIntake","laatsteIntake")
            ->innerJoin("laatsteIntake.intakelocatie","locatie")
//            ->andWhere("verslag.type = :mw_verslagType")
            ->andWhere("DATE(laatsteIntake.intakedatum) BETWEEN :startdatum AND :einddatum")
            ->andWhere("locatie.naam IN (:locatieNamen)")
//            ->setParameter("mw_verslagType",Verslag::TYPE_MW)
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

    private function getAantalClientenLocatie($startdatum,$einddatum,$locaties)
    {
        $builder = $this->createQueryBuilder("klant");
        $builder->select("COUNT(DISTINCT klant.id) AS aantalKlanten")
            ->innerJoin("klant.verslagen","verslag")
            ->innerJoin("verslag.locatie","locatie")
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