<?php

namespace AppBundle\Model;

use Doctrine\ORM\Query;

interface DaoHasDossierStatusInterface
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
    public function findCurrentDossierStatusses($dsClass, $entityFieldname): Query;

    public function findCurrentDossierStatusIds($dsClass, $entityFieldname): array;

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
    ): void;
}