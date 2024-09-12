<?php

namespace AppBundle\Model;


use Doctrine\ORM\Query;

trait DaoHasDossierStatusTrait
{

    /**
     * Generieke functie om de huidige dossierstatus op te vragen van een entiteit.
     * Dit maakt de basisquery minder complex. Omdat elk statusId slechts aan 1 entiteit kan hangen is deze query
     * impliciet dus gecorreleerd.
     * Alleen door beperkigen van Doctrine lukt het niet om hem netjes in DQL te stoppen. Dit is het beste compromis.
     *
     * @param $dsClass
     * @param $entityFieldname
     * @return mixed
     */
    public function findCurrentDossierStatusses($dsClass, $entityFieldname): Query
    {
        $query = $this->entityManager->createQuery("
            SELECT ds1
            FROM $dsClass ds1
            WHERE ds1.datum IN (
                SELECT MAX(ds2.datum)
                FROM $dsClass ds2
                WHERE ds2.$entityFieldname = ds1.$entityFieldname
            )
            AND ds1.id IN (
                SELECT MAX(ds3.id)
                FROM $dsClass ds3
                WHERE ds3.$entityFieldname = ds1.$entityFieldname AND ds3.datum = ds1.datum 
            )
            ORDER BY ds1.datum DESC, ds1.id DESC
        ");

        return $query;
    }

    public function findCurrentDossierStatusIds($dsClass, $entityFieldname): array
    {

        $builder = $this->findCurrentDossierStatusses($dsClass, $entityFieldname);
        $currentDss = $builder->getResult();
        // Prepare your array of maxIds
        $maxIds = array_map(function($item){
            return $item->getId();
        }, $currentDss);

        return $maxIds;
    }

    /**
     * @param $builder The builder where to add the dossierStatus fields and ids to
     * @param $alias The alias of the current entity which has a dossierStatus
     * @param $dossierStatusFieldname The name of the collection of dossier statussen in the entity
     * @param $entityFieldname The entity name in the DossierStatus class (reverse)
     * @param $dsClass The FQCN of the DossierStatus class.
     * @return void
     */
    public function addDossierStatusToQueryBuilder(
        $builder,
        $alias,
        $dossierStatusFieldname,
        $entityFieldname,
        $dsClass
    ): void
    {
        $currentDsIds = $this->findCurrentDossierStatusIds($dsClass, $entityFieldname);
        $builder
            ->innerJoin($alias.'.'.$dossierStatusFieldname, 'ds')
            ->andWhere('ds.id IN (:currentDsIds)')
            ->setParameter('currentDsIds',$currentDsIds)
        ;
    }

    public function findDossierStatusById($dsClass, $id): ?object
    {
        return $this->entityManager->getRepository($dsClass)->find($id);
    }
    public function removeDossierStatus(DossierStatusInterface $dossierStatus): bool
    {

        if ($dossierStatus) {
            $this->entityManager->remove($dossierStatus);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

    public function updateDossierStatus(DossierStatusInterface $dossierStatus): bool
    {

        if ($dossierStatus) {
            $this->entityManager->flush();
            return true;
        }
        return false;
    }
}