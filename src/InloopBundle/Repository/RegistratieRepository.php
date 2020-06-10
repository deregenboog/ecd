<?php


namespace InloopBundle\Repository;


use AppBundle\Model\Doelstellingcijfer;
use AppBundle\Repository\DoelstellingRepositoryInterface;
use AppBundle\Repository\DoelstellingRepositoryTrait;

class RegistratieRepository extends \Doctrine\ORM\EntityRepository implements \AppBundle\Repository\DoelstellingRepositoryInterface
{
    use DoelstellingRepositoryTrait;

    private $spuitomruilLocaties = [];

    public function setSpuitomruilLocaties(array $locaties)
    {
        $this->spuitomruilLocaties = $locaties;
    }

    public function getCategory(): string
    {
        return DoelstellingRepositoryInterface::CAT_INLOOP;
    }

    public function initDoelstellingcijfers(): void
    {
        $this->addDoelstellingcijfer(
            "Aantal unieke klanten dat een of meerdere keren is geregistreerd bij een inloophuis in de periode.",
            "3200",
            "Aantal unieke klanten",
            function($doelstelling,$startdatum,$einddatum)
            {
                $r = $this->getAantalUniekeBezoekers($startdatum,$einddatum);
                return $r;
            }
        );

        $this->addDoelstellingcijfer(
            "Gemiddelde bedbezetting per nacht voor nachtopvanglocatie(s)",
            "3201",
            "Gemiddelde bedbezetting",
            function($doelstelling,$startdatum,$einddatum)
            {
                $r = $this->getGemiddeldeBedbezettingNachtopvang($startdatum,$einddatum);
                return $r;
            }
        );
        $locatieStr = implode(", ",$this->spuitomruilLocaties);
        $this->addDoelstellingcijfer(
            "Optelling van aantal spuiten dat in inloophuizen op locatie(s) $locatieStr is omgeruild of verkocht en zo zijn vastgelegd in de registratie.",
            "3206",
            "Aantal spuiten",
            function($doelstelling,$startdatum,$einddatum)
            {
                $r = $this->getAantalSpuiten($startdatum,$einddatum,$this->spuitomruilLocaties);
                return $r;
            }
        );
    }

    private function getAantalUniekeBezoekers($startdatum,$einddatum)
    {
        $builder = $this->createQueryBuilder("registratie");
        $builder->select("COUNT(DISTINCT registratie.klant)")
            ->andWhere("DATE(registratie.binnen) BETWEEN DATE(:startdatum) AND DATE(:einddatum)")
            ->setParameter("startdatum",$startdatum)
            ->setParameter("einddatum",$einddatum)
            ;
        return $builder->getQuery()->getSingleScalarResult();
    }

    private function getGemiddeldeBedbezettingNachtopvang(\DateTime $startdatum,$einddatum)
    {
        $builder = $this->createQueryBuilder("registratie");
        $builder->select("COUNT(registratie.id)")
            ->innerJoin("registratie.locatie", "locatie")
            ->andWhere("locatie.nachtopvang = 1")
            ->andWhere("DATE(registratie.binnen) BETWEEN DATE(:startdatum) AND DATE(:einddatum)")
            ->setParameter("startdatum",$startdatum)
            ->setParameter("einddatum",$einddatum)
            ;
        $r = $builder->getQuery()->getSingleScalarResult();
        $numDays = $startdatum->diff($einddatum)->days;

        return ceil($r/$numDays);
    }

    private function getAantalSpuiten($startdatum,$einddatum,$locaties)
    {
        $builder = $this->createQueryBuilder("registratie");
        $builder->select("IFNULL(SUM(registratie.aantalSpuiten),0)")
            ->innerJoin("registratie.locatie", "locatie")
            ->andWhere("locatie.naam IN(:locaties)")
            ->andWhere("DATE(registratie.binnen) BETWEEN DATE(:startdatum) AND DATE(:einddatum)")
            ->setParameter("startdatum",$startdatum)
            ->setParameter("einddatum",$einddatum)
            ->setParameter("locaties",$locaties)
        ;
        $r = $builder->getQuery()->getSingleScalarResult();
//        $numDays = $startdatum->diff($einddatum)->days;

        return $r;
    }
}