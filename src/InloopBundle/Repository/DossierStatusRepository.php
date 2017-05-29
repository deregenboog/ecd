<?php

namespace InloopBundle\Repository;

use Doctrine\ORM\EntityRepository;
use InloopBundle\Entity\DossierStatus;
use InloopBundle\Entity\Aanmelding;
use Doctrine\ORM\Query\ResultSetMapping;
use AppBundle\Entity\Klant;

class DossierStatusRepository extends EntityRepository
{
    CONST CACHE_KEY_ACTIVE_KLANT_IDS = 'active_klant_ids';

    public function findCurrentByKlantId($id)
    {
        return $this->createQueryBuilder('status')
            ->innerJoin('status.klant', 'klant', 'WITH', 'status = klant.huidigeStatus')
            ->where('klant.id = :klant_id')
            ->setParameter('klant_id', $id)
            ->getQuery()
            ->getOneOrNUllResult()
        ;
    }

    /**
     * Returns the ids of "Klant" entities that have "Aanmelding" as most recent status, rather than "Afsluiting".
     *
     * @return array Array of ids.
     */
    public function getActiveKlantIds()
    {
        $klantIds = \Cache::read(self::CACHE_KEY_ACTIVE_KLANT_IDS);

        if (empty($klantIds)) {
            $builder = $this->getEntityManager()->getRepository(Aanmelding::class)->createQueryBuilder('status')
                ->select('klant.id AS klant_id')
                ->innerJoin('status.klant', 'klant', 'WITH', 'status = klant.huidigeStatus')
            ;

            $klantIds = [];
            foreach ($builder->getQuery()->getResult() as $status) {
                $klantIds[] = $status['klant_id'];
            }

            \Cache::write(self::CACHE_KEY_ACTIVE_KLANT_IDS, $klantIds);
        }

        return $klantIds;
    }
}
