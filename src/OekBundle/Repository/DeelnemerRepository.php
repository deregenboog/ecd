<?php

namespace OekBundle\Repository;

use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\Aanmelding;
use OekBundle\Entity\Afsluiting;
use OekBundle\Report\AbstractDeelnemersVerwezen;

class DeelnemerRepository extends EntityRepository
{
    public function countByTrainingAndStadsdeel($status, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect("CONCAT_WS(' - ', training.naam, groep.naam) AS trainingnaam")
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->leftJoin('appKlant.werkgebied', 'werkgebied')
            ->innerJoin('klant.deelnames', 'deelname')
            ->innerJoin('deelname.deelnameStatussen', 'deelnameStatus')
            ->innerJoin('deelname.training', 'training')
            ->innerJoin('training.groep', 'groep')
            ->where('deelnameStatus.status = :status')
            ->andWhere('deelnameStatus.datum BETWEEN :startDate AND :endDate')
            ->setParameter('status', $status)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('trainingnaam', 'stadsdeel')
        ;

        return $builder->getQuery()->getResult();
    }

    public function countByGroepAndStadsdeel($status, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('groep.naam AS groepnaam')
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->leftJoin('appKlant.werkgebied', 'werkgebied')
            ->innerJoin('klant.deelnames', 'deelname')
            ->innerJoin('deelname.deelnameStatussen', 'deelnameStatus')
            ->innerJoin('deelname.training', 'training')
            ->innerJoin('training.groep', 'groep')
            ->where('deelnameStatus.status = :status')
            ->andWhere('deelnameStatus.datum BETWEEN :startDate AND :endDate')
            ->setParameter('status', $status)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('groepnaam', 'stadsdeel')
        ;

        return $builder->getQuery()->getResult();
    }

    public function countByVerwijzing($verwezen, \DateTime $startDate, \DateTime $endDate)
    {
        $entities = [
            AbstractDeelnemersVerwezen::DOOR => Aanmelding::class,
            AbstractDeelnemersVerwezen::NAAR => Afsluiting::class,
        ];

        $entity = $entities[$verwezen];

        $builder = $this->getCountBuilder()
            ->addSelect('verwijzing.naam AS verwijzingsoort')
            ->innerJoin('klant.dossierStatussen', 'dossierStatus')
            ->innerJoin('dossierStatus.verwijzing', 'verwijzing')
            ->where("dossierStatus INSTANCE OF $entity")
            ->andWhere('dossierStatus.datum BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('verwijzingsoort')
        ;

        return $builder->getQuery()->getResult();
    }

    private function getCountBuilder()
    {
        return $this->createQueryBuilder('klant')
            ->select('COUNT(DISTINCT klant.id) AS aantal')
            ->innerJoin('klant.klant', 'appKlant')
        ;
    }
}
