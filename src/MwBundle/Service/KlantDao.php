<?php

namespace MwBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Intake;
use MwBundle\Entity\MwDossierStatus;
use MwBundle\Entity\Verslag;

class KlantDao extends AbstractDao implements KlantDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'klant.geboortedatum',
            'geslacht.volledig',
            'gebruikersruimte.naam',
            'locatie.naam',
            'project.naam',
            'laatsteVerslag.datum',
        ],
        'wrap-queries' => true,
    ];

    protected $class = Klant::class;

    protected $alias = 'klant';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias);
        $builder
            ->select('klant, geslacht, maatschappelijkWerker, info')
            ->leftJoin('klant.geslacht', 'geslacht')
            ->leftJoin('klant.maatschappelijkWerker','maatschappelijkWerker')
            ->leftJoin('klant.info', 'info')

            // huidige MW status
            ->addSelect('huidigeMwStatus')
            ->join('klant.mwStatussen', 'huidigeMwStatus')
            ->andWhere('huidigeMwStatus.id = FIRST(
                SELECT s.id
                FROM '.MwDossierStatus::class.' s
                WHERE IDENTITY(s.klant) = klant.id
                ORDER BY s.id DESC
            )')
            ->addSelect('project')
            ->leftJoin('huidigeMwStatus.project', 'project')

            // laatste verslag
            ->addSelect('laatsteVerslag')
            ->leftJoin('klant.verslagen', 'laatsteVerslag')
            ->andWhere('laatsteVerslag.id IS NULL OR laatsteVerslag.id = FIRST(
                SELECT v.id
                FROM '.Verslag::class.' v
                WHERE IDENTITY(v.klant) = klant.id
                ORDER BY v.id DESC
            )')
            ->addSelect('locatie')
            ->leftJoin('laatsteVerslag.locatie', 'locatie')

            // laatste intake
            ->addSelect('laatsteIntake')
            ->leftJoin('klant.intakes', 'laatsteIntake')
            ->andWhere('laatsteIntake.id IS NULL OR laatsteIntake.id = FIRST(
                SELECT i.id
                FROM '.Intake::class.' i
                WHERE IDENTITY(i.klant) = klant.id
                ORDER BY i.id DESC
            )')
            ->addSelect('gebruikersruimte')
            ->leftJoin('laatsteIntake.gebruikersruimte', 'gebruikersruimte')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * @param $startdatum
     * @param $einddatum
     * @param $locaties
     * @return false
     * @deprecated
     */
    public function findAllAfsluitredenenAfgeslotenKlantenForLocaties($startdatum, $einddatum,$locaties = [])
    {
        //Gebruik MwDossierStatus om dit soort dingen op te vragen.
      return false;
    }

    /**
     * @param $startdatum
     * @param $einddatum
     * @param $locaties
     * @return false
     * @deprecated
     */
    public function findAllNieuweKlantenForLocaties($startdatum, $einddatum,$locaties=[])
    {
        //Gebruik MwDossierStatus om dit soort dingen op te vragen.
        return false;
    }

    public function countResultatenPerLocatie($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('klant')
            /**
             * SELECT DISTINCT (klanten.id), mw_dossier_statussen.class, mw_resultaten.naam FROM klanten LEFT JOIN verslagen ON verslagen.klant_id = klanten.id
            LEFT JOIN mw_dossier_statussen ON mw_dossier_statussen.id = klanten.huidigeMwStatus_id
            LEFT JOIN mw_afsluiting_resultaat ON mw_afsluiting_resultaat.afsluiting_id = mw_dossier_statussen.id
            INNER JOIN mw_resultaten ON mw_resultaten.id = mw_afsluiting_resultaat.resultaat_id
            WHERE verslagen.id IS NOT NULL
            AND mw_dossier_statussen.class IN ('Afsluiting')
             */
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Klant $entity)
    {
        return parent::doCreate($entity);
    }

    public function update(Klant $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Klant $entity)
    {
        $this->doDelete($entity);
    }

}
