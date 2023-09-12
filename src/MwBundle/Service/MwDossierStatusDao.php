<?php

namespace MwBundle\Service;

use AppBundle\Service\AbstractDao;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\BinnenVia;
use MwBundle\Entity\BinnenViaOptieVW;
use MwBundle\Entity\MwDossierStatus;

class MwDossierStatusDao extends AbstractDao
{
    protected $class = MwDossierStatus::class;

    protected $alias = 'dossierstatus';

    /**
     * {inheritdoc}.
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * {inheritdoc}.
     */
    public function create($entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update($entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete($entity)
    {
        $this->doDelete($entity);
    }

    public function findAllAanmeldingenForLocaties($startDate,$endDate, $locaties = [])
    {
        $builder = $this->repository->createQueryBuilder($this->alias);
        $builder
            ->select("COUNT(DISTINCT dossierstatus.klant) AS aantal, locatie.naam AS naam")
            ->join("dossierstatus.locatie","locatie")
            ->where("locatie.naam IN (:locatienamen)")
            ->andWhere("dossierstatus.datum BETWEEN :startdate AND :enddate")
            ->andWhere("dossierstatus INSTANCE OF :class")
            ->groupBy("locatie.naam")
            ->setParameter("locatienamen",$locaties)
            ->setParameter("startdate",$startDate)
            ->setParameter("enddate",$endDate)
            ->setParameter("class","Aanmelding")
            ;
        return $builder->getQuery()->getResult();

    }

    public function findAllAanmeldingenBinnenVia($startDate,$endDate, $locaties = [])
    {
        $builder = $this->repository->createQueryBuilder($this->alias);
        $builder
            ->select("COUNT(DISTINCT dossierstatus.klant) AS aantal, binnenviaoptie.naam AS naam")
            ->join("dossierstatus.locatie","locatie")
            ->join("dossierstatus.binnenViaOptieKlant","binnenviaoptie")
            ->where("locatie.naam IN (:locatienamen)")
            ->andWhere("dossierstatus.datum BETWEEN :startdate AND :enddate")
            ->andWhere("dossierstatus INSTANCE OF :class")
            ->groupBy("binnenviaoptie.naam")
            ->setParameter("locatienamen",$locaties)
            ->setParameter("startdate",$startDate)
            ->setParameter("enddate",$endDate)
            ->setParameter("class","Aanmelding")
        ;
        return $builder->getQuery()->getResult();

    }
}
