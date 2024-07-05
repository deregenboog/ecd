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

    private static $projectenBeginstand;

    private static $projectenGestart;

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
            $this->kplProjectenSpecifiek[$item['kpl']] = $item['label'];
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
        foreach ($this->kplProjectenSpecifiek as $kpl => $label) {
            $this->addDoelstellingcijfer(
                "Beginstand koppelingen + gestartte koppelingen periode waabij project = $label",
                $kpl,
                $label,
                function ($doelstelling, $startdatum, $einddatum) use ($label) {
                    $r = $this->getKoppelingenForProject($label, $startdatum, $einddatum);

                    return $r;
                }
            );
        }

        $this->addDoelstellingcijfer(
            '1,6% van de beginstand koppelingen + gestartte koppelingen periode van alle IZ projecten minus de specifiek gerapporteerden',
            '2172',
            'De bres matrix',
            function ($doelstelling, $startdatum, $einddatum) {
                $r = $this->getKoppelingenForBasis($startdatum, $einddatum, 0.016);

                return $r;
            }
        );
        $this->addDoelstellingcijfer(
            '3,2% van de beginstand koppelingen + gestartte koppelingen periode van alle IZ projecten minus de specifiek gerapporteerden',
            '2178',
            'Vrienden matrix',
            function ($doelstelling, $startdatum, $einddatum) {
                $r = $this->getKoppelingenForBasis($startdatum, $einddatum, 0.032);

                return $r;
            }
        );

        $this->addDoelstellingcijfer(
            'De beginstand koppelingen + gestartte koppelingen periode van alle IZ projecten minus de specifiek gerapporteerden.',
            '2200',
            'IZ Sociale basis',
            function ($doelstelling, $startdatum, $einddatum) {
                $r = $this->getKoppelingenForBasis($startdatum, $einddatum, 0.952);

                return $r;
            }
        );
    }

    private function getKoppelingenForProject($projectNaam, $startdatum, $einddatum)
    {
        $this->initProjectenCounter($startdatum, $einddatum);
        $gestart = 0;
        if (array_key_exists($projectNaam, self::$projectenGestart)) {
            $gestart = self::$projectenGestart[$projectNaam];
        }

        $beginstand = 0;
        if (array_key_exists($projectNaam, self::$projectenBeginstand)) {
            $beginstand = self::$projectenBeginstand[$projectNaam];
        }

        return $beginstand + $gestart;
    }

    private function getKoppelingenForBasis($startdatum, $einddatum, $factor)
    {
        $this->initProjectenCounter($startdatum, $einddatum);

        $aantalTotaal = 0;
        foreach (self::$projectenBeginstand as $label => $aantal) {
            if (in_array($label, $this->kplProjectenSpecifiek)) {
                continue;
            }
            $aantalTotaal += $aantal;
        }
        foreach (self::$projectenGestart as $label => $aantal) {
            if (in_array($label, $this->kplProjectenSpecifiek)) {
                continue;
            }
            $aantalTotaal += $aantal;
        }

        return ceil($aantalTotaal * $factor);
    }

    private function initProjectenCounter($startdatum, $einddatum)
    {
        if (!is_array(self::$projectenBeginstand)) {
            self::$projectenBeginstand = $this->hulpvraagRepository->countKoppelingenByProject('beginstand', $startdatum, $einddatum);
            $t = [];
            foreach (self::$projectenBeginstand as $arr) {
                $t[$arr['projectnaam']] = $arr['aantal'];
            }
            self::$projectenBeginstand = $t;
        }

        if (!is_array(self::$projectenGestart)) {
            self::$projectenGestart = $this->hulpvraagRepository->countKoppelingenByProject('gestart', $startdatum, $einddatum);

            $t = [];
            foreach (self::$projectenGestart as $arr) {
                $t[$arr['projectnaam']] = $arr['aantal'];
            }
            self::$projectenGestart = $t;
        }
    }
}
