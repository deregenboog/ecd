<?php

namespace OekBundle\Repository;

use AppBundle\Repository\DoelstellingRepositoryInterface;
use AppBundle\Repository\DoelstellingRepositoryTrait;
use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\Aanmelding;
use OekBundle\Entity\Afsluiting;
use OekBundle\Entity\DeelnameStatus;
use OekBundle\Report\AbstractDeelnemersVerwezen;

class DeelnemerRepository extends EntityRepository implements DoelstellingRepositoryInterface
{
    use DoelstellingRepositoryTrait;

    public function countByGroepAndTraining($status, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('groep.naam AS groepnaam')
            ->addSelect('training.naam AS trainingnaam')
            ->innerJoin('klant.deelnames', 'deelname')
            ->innerJoin('deelname.deelnameStatussen', 'deelnameStatus')
            ->innerJoin('deelname.training', 'training')
            ->innerJoin('training.groep', 'groep')
            ->where('deelnameStatus.status = :status')
            ->andWhere('deelnameStatus.datum BETWEEN :startDate AND :endDate')
            ->setParameter('status', $status)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('groepnaam', 'trainingnaam')
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

    public function getCategory(): string
    {
        return DoelstellingRepositoryInterface::CAT_ACTIVERING;
    }

    public function initDoelstellingcijfers(): void
    {
        $stadsdelenResult = $this->getStadsdelen();
        foreach ($stadsdelenResult as $stadsdeel) {
            if(null == $werkgebied = $stadsdeel['werkgebiednaam']) continue;
            $this->addDoelstellingcijfer(
                "Aantal deelnemers dat een training heeft afgerond (per stadsdeel) in de periode.",
                "1760",
                "OEK ($werkgebied)",
                function($doelstelling,$startdatum,$einddatum) use ($werkgebied) {
                    $r = $this->countDeelnemersWithAfgerondeTrainingForStadsdeel($doelstelling,$startdatum,$einddatum,$werkgebied);
                    return $r;
                }
            );
        }

    }

    private function countDeelnemersWithAfgerondeTrainingForStadsdeel($doelstelling,$startdatum,$eindddatum,$stadsdeel)
    {
        $builder = $this->getCountBuilder()
            ->innerJoin('klant.deelnames', 'deelname')
            ->innerJoin('deelname.deelnameStatussen', 'deelnameStatus')
            ->innerJoin('deelname.training', 'training')
            ->innerJoin('training.groep', 'groep')
            ->where('deelnameStatus.status = :status')
            ->andWhere('deelnameStatus.datum BETWEEN :startDate AND :endDate')
            ->andWhere('appKlant.werkgebied = :stadsdeel')
            ->setParameter('status', DeelnameStatus::STATUS_AFGEROND)
            ->setParameter('startDate', $startdatum)
            ->setParameter('endDate', $eindddatum)
            ->setParameter('stadsdeel',$stadsdeel)
//            ->groupBy('groepnaam', 'trainingnaam')
        ;
        return $builder->getQuery()->getSingleScalarResult();
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
