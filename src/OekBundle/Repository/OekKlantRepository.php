<?php

namespace OekBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use OekBundle\Entity\OekAanmelding;
use OekBundle\Entity\OekAfsluiting;
use OekBundle\Report\AbstractKlantenVerwezen;

class OekKlantRepository extends EntityRepository
{
    public function countByTrainingAndStadsdeel($status, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('oekTraining.naam AS training')
            ->addSelect('klant.werkgebied AS stadsdeel')
            ->innerJoin('oekKlant.oekDeelnames', 'oekDeelname')
            ->innerJoin('oekDeelname.oekDeelnameStatussen', 'oekDeelnameStatus')
            ->innerJoin('oekDeelname.oekTraining', 'oekTraining')
            ->where('oekDeelnameStatus.status = :status')
            ->andWhere('oekDeelnameStatus.datum BETWEEN :startDate AND :endDate')
            ->setParameter('status', $status)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('oekTraining', 'klant.werkgebied')
        ;

        return $builder->getQuery()->getResult();
    }

    public function countByVerwijzing($verwezen, \DateTime $startDate, \DateTime $endDate)
    {
        $entities = [
            AbstractKlantenVerwezen::DOOR => OekAanmelding::class,
            AbstractKlantenVerwezen::NAAR => OekAfsluiting::class,
        ];

        $entity = $entities[$verwezen];

        $builder = $this->getCountBuilder()
            ->addSelect('oekVerwijzing.naam AS verwijzing')
            ->innerJoin('oekKlant.oekDossierStatussen', 'oekDossierStatus')
            ->innerJoin('oekDossierStatus.verwijzing', 'oekVerwijzing')
            ->where("oekDossierStatus INSTANCE OF $entity")
            ->where('oekDossierStatus.datum BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('oekVerwijzing')
        ;

        return $builder->getQuery()->getResult();
    }

    private function getCountBuilder()
    {
        return $this->createQueryBuilder('oekKlant')
            ->select('COUNT(DISTINCT oekKlant.id) AS aantal')
            ->innerJoin('oekKlant.klant', 'klant')
        ;
    }
}
