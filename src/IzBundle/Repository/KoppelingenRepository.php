<?php

namespace IzBundle\Repository;

use AppBundle\Repository\DoelstellingRepositoryInterface;
use AppBundle\Repository\DoelstellingRepositoryTrait;
use Doctrine\ORM\EntityRepository;

class KoppelingenRepository extends EntityRepository implements DoelstellingRepositoryInterface
{
    use DoelstellingRepositoryTrait;

    /**
     * @var HulpvraagRepository
     */
    private $hulpvraagRepository;

    private $projectenBeginstand;

    private $projectenGestart;

    private $kplProjectenSpecifiek = [];

    public function countByJaar($year)
    {
        return $this->createQueryBuilder('doelstelling')
            ->select('p.naam AS projectnaam, doelstelling.aantal')
            ->innerJoin('doelstelling.project', 'p')
            ->where('doelstelling.jaar = :jaar')
            ->setParameter('jaar', $year)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countByJaarWithoutStadsdeel($year)
    {
        return $this->createQueryBuilder('doelstelling')
            ->select('p.naam AS projectnaam, SUM(doelstelling.aantal) AS aantal')
            ->innerJoin('doelstelling.project', 'p')
            ->where('doelstelling.jaar = :jaar')
            ->andWhere('doelstelling.stadsdeel IS NULL')
            ->groupBy('doelstelling.project')
            ->setParameter('jaar', $year)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countByJaarAndProjectAndStadsdeel($year)
    {
        return $this->createQueryBuilder('doelstelling')
            ->select('p.naam AS projectnaam, s.naam AS stadsdeel, doelstelling.aantal')
            ->innerJoin('doelstelling.project', 'p')
            ->innerJoin('doelstelling.stadsdeel', 's')
            ->where('doelstelling.jaar = :jaar')
            ->groupBy('doelstelling.project, doelstelling.stadsdeel')
            ->setParameter('jaar', $year)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countByJaarAndProjectAndCategorie($year)
    {
        return $this->createQueryBuilder('doelstelling')
            ->select('p.naam AS projectnaam, doelstelling.categorie, doelstelling.aantal')
            ->innerJoin('doelstelling.project', 'p')
            ->where('doelstelling.jaar = :jaar')
            ->groupBy('doelstelling.project, doelstelling.categorie')
            ->setParameter('jaar', $year)
            ->getQuery()
            ->getResult()
        ;
    }

    public function setSpecifiekeProjecten($arr)
    {
        foreach ($arr as $item) {
            $this->kplProjectenSpecifiek[$item["kpl"]] = $item["label"];
        }
    }

    public function setHulpvraagRepository(HulpvraagRepository $hulpvraagRepository)
    {
        $this->hulpvraagRepository = $hulpvraagRepository;
    }

    public function getCategory(): string
    {
        return DoelstellingRepositoryInterface::CAT_IZ;
    }

    public function initDoelstellingcijfers(): void
    {
        // TODO: Implement initDoelstellingcijfers() method.

        foreach ($this->kplProjectenSpecifiek as $kpl => $label) {
            $this->addDoelstellingcijfer(
                "Beginstand koppelingen + gestartte koppelingen periode waabij project = $label",
                $kpl,
                $label,
                function($doelstelling,$startdatum,$einddatum) use ($label)
                {
                    $r = $this->getKoppelingenForProject($label,$startdatum,$einddatum);
                    return $r;
                }
            );
        }

        $this->addDoelstellingcijfer(
            "1,6% van de beginstand koppelingen + gestartte koppelingen periode van alle IZ projecten minus de specifiek gerapporteerden",
            "2172",
            "De bres matrix",
            function($doelstelling,$startdatum,$einddatum) use ($label)
            {
                $r = $this->getKoppelingenForBasis($startdatum,$einddatum,0.016);
                return $r;
            }
        );
        $this->addDoelstellingcijfer(
            "3,2% van de beginstand koppelingen + gestartte koppelingen periode van alle IZ projecten minus de specifiek gerapporteerden",
            "2178",
            "Vrienden matrix",
            function($doelstelling,$startdatum,$einddatum) use ($label)
            {
                $r = $this->getKoppelingenForBasis($startdatum,$einddatum,0.032);
                return $r;
            }
        );

        $this->addDoelstellingcijfer(
            "De beginstand koppelingen + gestartte koppelingen periode van alle IZ projecten minus de specifiek gerapporteerden.",
            "2200",
            "IZ Sociale basis",
            function($doelstelling,$startdatum,$einddatum)
            {
                $r = $this->getKoppelingenForBasis($startdatum,$einddatum,0.952);
                return $r;
            }
        );


    }

    private function getKoppelingenForProject($projectNaam,$startdatum,$einddatum)
    {
        $this->initProjectenCounter($startdatum,$einddatum);

        $gestart = 0;
        if(array_key_exists($projectNaam,$this->projectenGestart))
        {
            $gestart = $this->projectenGestart[$projectNaam];
        }
        $beginstand = 0;
        if(array_key_exists($projectNaam,$this->projectenBeginstand))
        {
            $beginstand = $this->projectenBeginstand[$projectNaam];
        }

        return $beginstand+$gestart;
    }

    private function getKoppelingenForBasis($startdatum,$einddatum,$factor)
    {
        $this->initProjectenCounter($startdatum,$einddatum);
        foreach ($this->kplProjectenSpecifiek as $kpl=>$label) {
            if(array_key_exists($label,$this->projectenGestart))
            {
                unset($this->projectenGestart[$label]);
            }
            if(array_key_exists($label,$this->projectenBeginstand))
            {
                unset($this->projectenBeginstand[$label]);
            }
        }
        $aantalTotaal = 0;
        foreach ($this->projectenBeginstand as $label=>$aantal) {

            $aantalTotaal +=$aantal;
        }
        foreach ($this->projectenGestart as $label=>$aantal) {
            $aantalTotaal+=$aantal;
        }
        return ceil($aantalTotaal * $factor);

    }

    private function initProjectenCounter($startdatum,$einddatum)
    {
        if(null === $this->projectenBeginstand)
        {
            $this->projectenBeginstand = $this->hulpvraagRepository->countKoppelingenByProject("beginstand",$startdatum,$einddatum);
            $t = array();
            foreach ($this->projectenBeginstand as $arr) {
                $t[$arr["projectnaam"]] = $arr["aantal"];
            }
            $this->projectenBeginstand = $t;
        }

        if(null === $this->projectenGestart)
        {
            $this->projectenGestart = $this->hulpvraagRepository->countKoppelingenByProject("gestart",$startdatum,$einddatum);
            $t = array();
            foreach ($this->projectenGestart as $arr) {
                $t[$arr["projectnaam"]] = $arr["aantal"];
            }
            $this->projectenGestart = $t;
        }
    }
}
