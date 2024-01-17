<?php

namespace OekBundle\Repository;

use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\Aanmelding;
use OekBundle\Entity\Afsluiting;
use OekBundle\Entity\DeelnameStatus;
use OekBundle\Report\AbstractDeelnemersVerwezen;

class DeelnameRepository extends EntityRepository
{
    private function getCountBuilder()
    {
        return $this->createQueryBuilder('deelname')
            ->select('COUNT(DISTINCT deelnemer.id) AS aantal')
            ->innerJoin('deelname.deelnemer', 'deelnemer')

        ;
    }

    public function getAantalDeelnamesPerStadsdeel($startdatum,$eindddatum)
    {
        $builder = $this->getCountBuilder()
            ->innerjoin('deelnemer.klant','appKlant')
            ->innerJoin("appKlant.werkgebied",'werkgebied')
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->innerJoin('deelname.deelnameStatussen', 'deelnameStatus')
            ->innerJoin('deelname.training', 'training')
            ->innerJoin('training.groep', 'groep')
//            ->where('deelnameStatus.status = :status')
            ->andWhere('deelnameStatus.datum BETWEEN :startDate AND :endDate')

//            ->setParameter('status', DeelnameStatus::STATUS_AFGEROND)
            ->setParameter('startDate', $startdatum)
            ->setParameter('endDate', $eindddatum)

            ->groupBy('werkgebied.naam')
        ;
        return $builder->getQuery()->getResult();
    }
    private function getStadsdelen()
    {
        $builder =  $this->createQueryBuilder('training')
            ->select('werkgebied.naam AS werkgebiednaam')
            ->innerJoin('training.deelnames', 'deelname')
            ->innerJoin('deelname.deelnemer', 'klant')
            ->innerJoin('klant.klant', 'appKlant')
            ->leftJoin('appKlant.werkgebied', 'werkgebied')
            ->groupBy('werkgebiednaam');

//        $sql = $builder->getQuery()->getSQL();
//        $parameters = $builder->getQuery()->getParameters();
        return $builder->getQuery()->getResult();
    }
}
