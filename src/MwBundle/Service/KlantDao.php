<?php

namespace MwBundle\Service;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use MwBundle\Entity\Aanmelding;

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
            'medewerker.voornaam',
            'maatschappelijkWerker.voornaam',
            'verslag',
            'gebruikersruimte.naam',
            'laatsteIntakeLocatie.naam',
            'klant.intakedatum',
            'datumLaatsteVerslag',
            'aantalVerslagen',
        ],
//        'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = Klant::class;

    protected $alias = 'klant';

    public function findAll($page = null, FilterInterface $filter = null)
    {
//        @TODO: Query herschrijven zodat ie voldoen aan FULL GROUP BY. zie https://www.percona.com/blog/2019/05/13/solve-query-failures-regarding-only_full_group_by-sql-mode/

        /**
         * SELECT
        # MAX(v0_.datum) AS datum_0,
        l1_.naam AS naam_1,
        #    COUNT(DISTINCT v0_.id) AS sclr_2,
        k2_.MezzoID AS MezzoID_3, k2_.laatste_TBC_controle AS laatste_TBC_controle_4, k2_.first_intake_date AS first_intake_date_5, k2_.last_zrm AS last_zrm_6, k2_.overleden AS overleden_7, k2_.doorverwijzen_naar_amoc AS doorverwijzen_naar_amoc_8, k2_.corona_besmet_vanaf AS corona_besmet_vanaf_9, k2_.id AS id_10, k2_.deleted AS deleted_11, k2_.BSN AS BSN_12, k2_.geen_post AS geen_post_13, k2_.geen_email AS geen_email_14, k2_.opmerking AS opmerking_15, k2_.disabled AS disabled_16, k2_.roepnaam AS roepnaam_17, k2_.geboortedatum AS geboortedatum_18, k2_.voornaam AS voornaam_19, k2_.tussenvoegsel AS tussenvoegsel_20, k2_.achternaam AS achternaam_21, k2_.adres AS adres_22, k2_.postcode AS postcode_23, k2_.plaats AS plaats_24, k2_.email AS email_25, k2_.mobiel AS mobiel_26, k2_.telefoon AS telefoon_27, k2_.created AS created_28, k2_.modified AS modified_29,
        i3_.id AS id_30, i3_.datum_intake AS datum_intake_31, i3_.amoc_toegang_tot AS amoc_toegang_tot_32, i3_.ondro_bong_toegang_van AS ondro_bong_toegang_van_33, i3_.overigen_toegang_van AS overigen_toegang_van_34, i3_.postadres AS postadres_35, i3_.postcode AS postcode_36, i3_.woonplaats AS woonplaats_37, i3_.telefoonnummer AS telefoonnummer_38, i3_.toegang_inloophuis AS toegang_inloophuis_39, i3_.mag_gebruiken AS mag_gebruiken_40, i3_.inkomen_overig AS inkomen_overig_41, i3_.legitimatie_nummer AS legitimatie_nummer_42, i3_.legitimatie_geldig_tot AS legitimatie_geldig_tot_43, i3_.verblijf_in_NL_sinds AS verblijf_in_NL_sinds_44, i3_.verblijf_in_amsterdam_sinds AS verblijf_in_amsterdam_sinds_45, i3_.opmerking_andere_instanties AS opmerking_andere_instanties_46, i3_.medische_achtergrond AS medische_achtergrond_47, i3_.verwachting_dienstaanbod AS verwachting_dienstaanbod_48, i3_.toekomstplannen AS toekomstplannen_49, i3_.indruk AS indruk_50, i3_.informele_zorg AS informele_zorg_51, i3_.dagbesteding AS dagbesteding_52, i3_.inloophuis AS inloophuis_53, i3_.hulpverlening AS hulpverlening_54, i3_.doelgroep AS doelgroep_55, i3_.verslaving_overig AS verslaving_overig_56, i3_.eerste_gebruik AS eerste_gebruik_57, i3_.geinformeerd_opslaan_gegevens AS geinformeerd_opslaan_gegevens_58, i3_.created AS created_59, i3_.modified AS modified_60, l4_.id AS id_61,
        l4_.naam AS naam_62, l4_.nachtopvang AS nachtopvang_63, l4_.gebruikersruimte AS gebruikersruimte_64, l4_.maatschappelijkwerk AS maatschappelijkwerk_65, l4_.tbc_check AS tbc_check_66, l4_.wachtlijst AS wachtlijst_67, l4_.datum_van AS datum_van_68, l4_.datum_tot AS datum_tot_69, l4_.created AS created_70, l4_.modified AS modified_71,
        k2_.werkgebied AS werkgebied_72, k2_.postcodegebied AS postcodegebied_73, k2_.first_intake_id AS first_intake_id_74, k2_.laste_intake_id AS laste_intake_id_75, k2_.huidigeStatus_id AS huidigeStatus_id_76, k2_.huidigeMwStatus_id AS huidigeMwStatus_id_77, k2_.mwBinnenViaOptieKlant_id AS mwBinnenViaOptieKlant_id_78, k2_.laatste_registratie_id AS laatste_registratie_id_79, k2_.merged_id AS merged_id_80, k2_.partner_id AS partner_id_81, k2_.maatschappelijkWerker_id AS maatschappelijkWerker_id_82, k2_.medewerker_id AS medewerker_id_83, k2_.land_id AS land_id_84, k2_.nationaliteit_id AS nationaliteit_id_85, k2_.geslacht_id AS geslacht_id_86,
        i3_.klant_id AS klant_id_87, i3_.medewerker_id AS medewerker_id_88, i3_.locatie2_id AS locatie2_id_89, i3_.locatie1_id AS locatie1_id_90, i3_.locatie3_id AS locatie3_id_91, i3_.verblijfstatus_id AS verblijfstatus_id_92, i3_.legitimatie_id AS legitimatie_id_93, i3_.woonsituatie_id AS woonsituatie_id_94, i3_.infobaliedoelgroep_id AS infobaliedoelgroep_id_95, i3_.primaireProblematiek_id AS primaireProblematiek_id_96, i3_.primaireproblematieksfrequentie_id AS primaireproblematieksfrequentie_id_97, i3_.primaireproblematieksperiode_id AS primaireproblematieksperiode_id_98, i3_.verslavingsfrequentie_id AS verslavingsfrequentie_id_99, i3_.verslavingsperiode_id AS verslavingsperiode_id_100, i3_.zrm_id AS zrm_id_101
        FROM klanten k2_
        LEFT JOIN verslagen v0_ ON k2_.id = v0_.klant_id
        LEFT JOIN medewerkers m5_ ON v0_.medewerker_id = m5_.id
        LEFT JOIN medewerkers m6_ ON k2_.maatschappelijkWerker_id = m6_.id
        LEFT JOIN verslagen v7_ ON k2_.id = v7_.klant_id AND (v0_.klant_id = v7_.klant_id AND (v0_.datum < v7_.datum OR (v0_.datum = v7_.datum AND v0_.id < v7_.id)))
        LEFT JOIN geslachten g8_ ON k2_.geslacht_id = g8_.id
        LEFT JOIN intakes i9_ ON k2_.id = i9_.klant_id
        LEFT JOIN intakes i3_ ON k2_.laste_intake_id = i3_.id
        LEFT JOIN locaties l10_ ON i3_.locatie2_id = l10_.id
        LEFT JOIN locaties l1_ ON v0_.locatie_id = l1_.id
        LEFT JOIN mw_dossier_statussen m11_ ON k2_.huidigeMwStatus_id = m11_.id AND m11_.class IN ('Aanmelding', 'Afsluiting')
        LEFT JOIN locaties l4_ ON i3_.locatie1_id = l4_.id
        WHERE (v7_.id IS NULL AND m11_.class IN ('Aanmelding') AND k2_.huidigeMwStatus_id IS NOT NULL) AND ((k2_.disabled IS NULL OR k2_.disabled = 0)) AND (k2_.deleted IS NULL)
        # GROUP BY k2_.achternaam, k2_.id
         */
        /**
         * @var KlantFilter $filter
         */
        $filter;

        $builder = $this->repository->createQueryBuilder($this->alias);
        $builder
            ->select('klant, laatsteIntake, gebruikersruimte')
            ->addSelect('verslag.datum AS datumLaatsteVerslag')
            ->addSelect('locatie.naam AS laatsteVerslagLocatie')
            ->addSelect('info.isGezin AS isGezin')
            ->addSelect('COUNT(DISTINCT verslag.id) AS aantalVerslagen')

            ;
//        if($filter->isDirty()) //rrrright. dit mag weg?
//        {
//            $builder
//                ->leftJoin($this->alias.'.verslagen', 'verslag')
//                ->leftJoin('verslag.medewerker','medewerker');
//        }
//        else
//        {
            $builder
                ->leftJoin($this->alias.'.verslagen', 'verslag')
                ->leftJoin('verslag.medewerker','medewerker')
                 ->leftJoin('klant.info','info');
//        }
        $builder
            ->leftJoin('klant.maatschappelijkWerker','maatschappelijkWerker')
            ->leftJoin($this->alias.'.verslagen','v2','WITH','verslag.klant = v2.klant AND (verslag.datum < v2.datum OR (verslag.datum = v2.datum AND verslag.id < v2.id))')
            ->where('v2.id IS NULL')
            ->leftJoin($this->alias.'.geslacht', 'geslacht')
//            ->addSelect('\'2020-07-01\' AS datumLaatsteVerslag')
//            ->addSelect('1 AS aantalVerslagen')
//            ->leftJoin($this->alias.'.huidigeStatus', 'status')
            ->leftJoin($this->alias.'.intakes', 'intake')
            ->leftJoin($this->alias.'.laatsteIntake', 'laatsteIntake')
            ->leftJoin('laatsteIntake.intakelocatie', 'laatsteIntakeLocatie')
            ->leftJoin('verslag.locatie','locatie')
            ->leftJoin($this->alias.'.huidigeMwStatus', 'huidigeMwStatus')
            ->leftJoin('laatsteIntake.gebruikersruimte', 'gebruikersruimte')

            ->groupBy('klant.achternaam, klant.id')
            ;


        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            /**
             * Vanwege de vele left joins in deze query is de total count niet te optimaliseren (door mij) onder de <900ms.
             * Dat vind ik lang. Temeer omdat in veel gevallen die total count niet bijster interessant is.
             *
             * In de default configuratie van het filter wordt daarom de total count niet geteld. Dat scheelt een eerste snelle klik.
             * Zodra je dan filtert gaat het sowieso sneller en wordt het juiste getal getoont.
             *
             */
            $dql = $builder->getDQL();
            $params = $builder->getParameters();
//            $sql = $builder->getQuery()->getSQL();

            $count = 11111;
            if($filter->isDirty())
            {
                $builder->resetDQLPart("select");
                $builder->select("klant.id");//select only one column for count query.

                $countQuery = $builder->getQuery();
                $fullSql = SqlExtractor::getFullSQL($countQuery);//including bound parameters.

                $countSql = "SELECT COUNT(*) AS count FROM (".$fullSql.") tmp"; //wrap into subquery.
                $countStmt = $this->entityManager->getConnection()->prepare($countSql);
                $countStmt->execute();
                $count = $countStmt->fetchColumn(0);
            }
            $query = $this->entityManager->createQuery($dql)->setHint('knp_paginator.count', $count);

            $query->setParameters($params);
            return $this->paginator->paginate($query, $page, $this->itemsPerPage,$this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
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
