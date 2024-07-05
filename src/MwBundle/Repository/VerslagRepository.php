<?php

namespace MwBundle\Repository;

use AppBundle\Entity\Klant;
use AppBundle\Repository\DoelstellingRepositoryInterface;
use AppBundle\Repository\DoelstellingRepositoryTrait;
use Doctrine\ORM\EntityRepository;

class VerslagRepository extends EntityRepository implements DoelstellingRepositoryInterface
{
    use DoelstellingRepositoryTrait;

    private $gezinNoodopvangLocaties = [];

    public function setGezinNoodopvangLocaties(array $gezinNoodopvangLocaties): void
    {
        $this->gezinNoodopvangLocaties = $gezinNoodopvangLocaties;
    }

    public function getTwVerslagenForKlant(Klant $klant)
    {
        return $this->createQueryBuilder('v')
            ->join('v.klant', 'klant')
            ->where('v.delenTw = :delenTw')
            ->andWhere('klant.id = :klantId')
            ->setParameter('delenTw', true)
            ->setParameter('klantId', $klant->getId())
            ->getQuery()
            ->getResult();
    }

    public function getCategory(): string
    {
        return DoelstellingRepositoryInterface::CAT_HULPVERLENING;
    }

    public function initDoelstellingcijfers(): void
    {
        $this->addDoelstellingcijfer(
            'Alle vastgelegde verslagen in periode',
            '4150',
            'MW',
            function ($doelstelling, $startdatum, $einddatum) {
                $r = $this->getAantalVerslagen($startdatum, $einddatum);

                return $r;
            }
        );
        $locatieStr = implode(', ', $this->gezinNoodopvangLocaties);
        $this->addDoelstellingcijfer(
            "Aantal clienten dat verslag heeft in de periode dat gemaakt is op de locatie(s) $locatieStr .",
            '4155',
            'Gezinnen noodopvang',
            function ($doelstelling, $startdatum, $einddatum) {
                $r = $this->getAantalUniekeClientenVoorLocaties($startdatum, $einddatum, $this->gezinNoodopvangLocaties);

                return $r;
            }
        );
    }

    private function getAantalUniekeClientenVoorLocaties($startdatum, $einddatum, $locaties)
    {
        $builder = $this->createQueryBuilder('verslag');
        $builder->select('COUNT(DISTINCT verslag.klant) AS aantalKlanten')
            ->leftJoin('verslag.klant', 'klant')
            ->innerJoin('verslag.locatie', 'locatie')
            ->andWhere('verslag.type = 1')
            ->andWhere('locatie.naam IN (:locaties)')
            ->andWhere('DATE(verslag.datum) BETWEEN :startdatum AND :einddatum')
            ->setParameter('startdatum', $startdatum)
            ->setParameter('einddatum', $einddatum)
            ->setParameter('locaties', $locaties)
        ;

        $query = $builder->getQuery();
        $sql = $query->getSQL();
        $parameters = $query->getParameters();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    private function getAantalVerslagen($startdatum, $einddatum)
    {
        $builder = $this->createQueryBuilder('verslag');
        $builder->select('COUNT(verslag.id) AS aantalVerslagen')
            ->leftJoin('verslag.klant', 'klant')
            ->andWhere('verslag.type = 1')
            ->andWhere('DATE(verslag.datum) BETWEEN :startdatum AND :einddatum')
            ->setParameter('startdatum', $startdatum)
            ->setParameter('einddatum', $einddatum)
        ;

        $query = $builder->getQuery();
        $sql = $query->getSQL();
        $parameters = $query->getParameters();
        $result = $query->getSingleScalarResult();

        return $result;
    }
}
