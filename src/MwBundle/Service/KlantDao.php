<?php

namespace MwBundle\Service;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Afsluiting;
use PHPUnit\Framework\Error\Deprecated;

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
       'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = Klant::class;

    protected $alias = 'klant';

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        //        @TODO: Query herschrijven zodat ie voldoen aan FULL GROUP BY. zie https://www.percona.com/blog/2019/05/13/solve-query-failures-regarding-only_full_group_by-sql-mode/

        /**
         * SELECT
         * # MAX(v0_.datum) AS datum_0,
         * l1_.naam AS naam_1,
         * #    COUNT(DISTINCT v0_.id) AS sclr_2,
         * k2_.MezzoID AS MezzoID_3, k2_.laatste_TBC_controle AS laatste_TBC_controle_4, k2_.first_intake_date AS first_intake_date_5, k2_.last_zrm AS last_zrm_6, k2_.overleden AS overleden_7, k2_.doorverwijzen_naar_amoc AS doorverwijzen_naar_amoc_8, k2_.corona_besmet_vanaf AS corona_besmet_vanaf_9, k2_.id AS id_10, k2_.deleted AS deleted_11, k2_.BSN AS BSN_12, k2_.geen_post AS geen_post_13, k2_.geen_email AS geen_email_14, k2_.opmerking AS opmerking_15, k2_.disabled AS disabled_16, k2_.roepnaam AS roepnaam_17, k2_.geboortedatum AS geboortedatum_18, k2_.voornaam AS voornaam_19, k2_.tussenvoegsel AS tussenvoegsel_20, k2_.achternaam AS achternaam_21, k2_.adres AS adres_22, k2_.postcode AS postcode_23, k2_.plaats AS plaats_24, k2_.email AS email_25, k2_.mobiel AS mobiel_26, k2_.telefoon AS telefoon_27, k2_.created AS created_28, k2_.modified AS modified_29,
         * i3_.id AS id_30, i3_.datum_intake AS datum_intake_31, i3_.amoc_toegang_tot AS amoc_toegang_tot_32, i3_.ondro_bong_toegang_van AS ondro_bong_toegang_van_33, i3_.overigen_toegang_van AS overigen_toegang_van_34, i3_.postadres AS postadres_35, i3_.postcode AS postcode_36, i3_.woonplaats AS woonplaats_37, i3_.telefoonnummer AS telefoonnummer_38, i3_.toegang_inloophuis AS toegang_inloophuis_39, i3_.mag_gebruiken AS mag_gebruiken_40, i3_.inkomen_overig AS inkomen_overig_41, i3_.legitimatie_nummer AS legitimatie_nummer_42, i3_.legitimatie_geldig_tot AS legitimatie_geldig_tot_43, i3_.verblijf_in_NL_sinds AS verblijf_in_NL_sinds_44, i3_.verblijf_in_amsterdam_sinds AS verblijf_in_amsterdam_sinds_45, i3_.opmerking_andere_instanties AS opmerking_andere_instanties_46, i3_.medische_achtergrond AS medische_achtergrond_47, i3_.verwachting_dienstaanbod AS verwachting_dienstaanbod_48, i3_.toekomstplannen AS toekomstplannen_49, i3_.indruk AS indruk_50, i3_.informele_zorg AS informele_zorg_51, i3_.dagbesteding AS dagbesteding_52, i3_.inloophuis AS inloophuis_53, i3_.hulpverlening AS hulpverlening_54, i3_.doelgroep AS doelgroep_55, i3_.verslaving_overig AS verslaving_overig_56, i3_.eerste_gebruik AS eerste_gebruik_57, i3_.geinformeerd_opslaan_gegevens AS geinformeerd_opslaan_gegevens_58, i3_.created AS created_59, i3_.modified AS modified_60, l4_.id AS id_61,
         * l4_.naam AS naam_62, l4_.nachtopvang AS nachtopvang_63, l4_.gebruikersruimte AS gebruikersruimte_64, l4_.maatschappelijkwerk AS maatschappelijkwerk_65, l4_.tbc_check AS tbc_check_66, l4_.wachtlijst AS wachtlijst_67, l4_.datum_van AS datum_van_68, l4_.datum_tot AS datum_tot_69, l4_.created AS created_70, l4_.modified AS modified_71,
         * k2_.werkgebied AS werkgebied_72, k2_.postcodegebied AS postcodegebied_73, k2_.first_intake_id AS first_intake_id_74, k2_.laste_intake_id AS laste_intake_id_75, k2_.huidigeStatus_id AS huidigeStatus_id_76, k2_.huidigeMwStatus_id AS huidigeMwStatus_id_77, k2_.mwBinnenViaOptieKlant_id AS mwBinnenViaOptieKlant_id_78, k2_.laatste_registratie_id AS laatste_registratie_id_79, k2_.merged_id AS merged_id_80, k2_.partner_id AS partner_id_81, k2_.maatschappelijkWerker_id AS maatschappelijkWerker_id_82, k2_.medewerker_id AS medewerker_id_83, k2_.land_id AS land_id_84, k2_.nationaliteit_id AS nationaliteit_id_85, k2_.geslacht_id AS geslacht_id_86,
         * i3_.klant_id AS klant_id_87, i3_.medewerker_id AS medewerker_id_88, i3_.locatie2_id AS locatie2_id_89, i3_.locatie1_id AS locatie1_id_90, i3_.locatie3_id AS locatie3_id_91, i3_.verblijfstatus_id AS verblijfstatus_id_92, i3_.legitimatie_id AS legitimatie_id_93, i3_.woonsituatie_id AS woonsituatie_id_94, i3_.infobaliedoelgroep_id AS infobaliedoelgroep_id_95, i3_.primaireProblematiek_id AS primaireProblematiek_id_96, i3_.primaireproblematieksfrequentie_id AS primaireproblematieksfrequentie_id_97, i3_.primaireproblematieksperiode_id AS primaireproblematieksperiode_id_98, i3_.verslavingsfrequentie_id AS verslavingsfrequentie_id_99, i3_.verslavingsperiode_id AS verslavingsperiode_id_100, i3_.zrm_id AS zrm_id_101
         * FROM klanten k2_
         * LEFT JOIN verslagen v0_ ON k2_.id = v0_.klant_id
         * LEFT JOIN medewerkers m5_ ON v0_.medewerker_id = m5_.id
         * LEFT JOIN medewerkers m6_ ON k2_.maatschappelijkWerker_id = m6_.id
         * LEFT JOIN verslagen v7_ ON k2_.id = v7_.klant_id AND (v0_.klant_id = v7_.klant_id AND (v0_.datum < v7_.datum OR (v0_.datum = v7_.datum AND v0_.id < v7_.id)))
         * LEFT JOIN geslachten g8_ ON k2_.geslacht_id = g8_.id
         * LEFT JOIN intakes i9_ ON k2_.id = i9_.klant_id
         * LEFT JOIN intakes i3_ ON k2_.laste_intake_id = i3_.id
         * LEFT JOIN locaties l10_ ON i3_.locatie2_id = l10_.id
         * LEFT JOIN locaties l1_ ON v0_.locatie_id = l1_.id
         * LEFT JOIN mw_dossier_statussen m11_ ON k2_.huidigeMwStatus_id = m11_.id AND m11_.class IN ('Aanmelding', 'Afsluiting')
         * LEFT JOIN locaties l4_ ON i3_.locatie1_id = l4_.id
         * WHERE (v7_.id IS NULL AND m11_.class IN ('Aanmelding') AND k2_.huidigeMwStatus_id IS NOT NULL) AND ((k2_.disabled IS NULL OR k2_.disabled = 0)) AND (k2_.deleted IS NULL)
         * # GROUP BY k2_.achternaam, k2_.id.
         */
        /**
         * @var KlantFilter $filter
         */
        $builder = $this->repository->createQueryBuilder($this->alias);

        /**OLD SQL
         EXPLAIN SELECT
  klanten.briefadres AS briefadres_0,
  klanten.MezzoID AS MezzoID_1,
  klanten.laatste_TBC_controle AS laatste_TBC_controle_2,
  klanten.first_intake_date AS first_intake_date_3,
  klanten.last_zrm AS last_zrm_4,
  klanten.overleden AS overleden_5,
  klanten.doorverwijzen_naar_amoc AS doorverwijzen_naar_amoc_6,
  klanten.corona_besmet_vanaf AS corona_besmet_vanaf_7,
  klanten.deleted AS deleted_8,
  klanten.BSN AS BSN_9,
  klanten.geen_post AS geen_post_10,
  klanten.geen_email AS geen_email_11,
  klanten.opmerking AS opmerking_12,
  klanten.disabled AS disabled_13,
  klanten.created AS created_14,
  klanten.modified AS modified_15,
  klanten.id AS id_16,
  klanten.roepnaam AS roepnaam_17,
  klanten.geboortedatum AS geboortedatum_18,
  klanten.voornaam AS voornaam_19,
  klanten.tussenvoegsel AS tussenvoegsel_20,
  klanten.achternaam AS achternaam_21,
  klanten.adres AS adres_22,
  klanten.postcode AS postcode_23,
  klanten.plaats AS plaats_24,
  klanten.email AS email_25,
  klanten.mobiel AS mobiel_26,
  klanten.telefoon AS telefoon_27,
  verslagen1.datum AS datum_28,
  locaties2.naam AS naam_29,
  info.isGezin AS isGezin_30,
  COUNT(DISTINCT verslagen1.id) AS sclr_31,
  klanten.briefadres AS briefadres_32,
  klanten.MezzoID AS MezzoID_33,
  klanten.laatste_TBC_controle AS laatste_TBC_controle_34,
  klanten.first_intake_date AS first_intake_date_35,
  klanten.last_zrm AS last_zrm_36,
  klanten.overleden AS overleden_37,
  klanten.doorverwijzen_naar_amoc AS doorverwijzen_naar_amoc_38,
  klanten.corona_besmet_vanaf AS corona_besmet_vanaf_39,
  klanten.deleted AS deleted_40,
  klanten.BSN AS BSN_41,
  klanten.geen_post AS geen_post_42,
  klanten.geen_email AS geen_email_43,
  klanten.opmerking AS opmerking_44,
  klanten.disabled AS disabled_45,
  klanten.created AS created_46,
  klanten.modified AS modified_47,
  klanten.id AS id_48,
  klanten.roepnaam AS roepnaam_49,
  klanten.geboortedatum AS geboortedatum_50,
  klanten.voornaam AS voornaam_51,
  klanten.tussenvoegsel AS tussenvoegsel_52,
  klanten.achternaam AS achternaam_53,
  klanten.adres AS adres_54,
  klanten.postcode AS postcode_55,
  klanten.plaats AS plaats_56,
  klanten.email AS email_57,
  klanten.mobiel AS mobiel_58,
  klanten.telefoon AS telefoon_59,
  intakes1.id AS id_60,
  intakes1.datum_intake AS datum_intake_61,
  intakes1.overigen_toegang_van AS overigen_toegang_van_62,
  intakes1.postadres AS postadres_63,
  intakes1.postcode AS postcode_64,
  intakes1.woonplaats AS woonplaats_65,
  intakes1.telefoonnummer AS telefoonnummer_66,
  intakes1.toegang_inloophuis AS toegang_inloophuis_67,
  intakes1.beschikking_wachtlijstbegeleiding AS beschikking_wachtlijstbegeleiding_68,
  intakes1.mag_gebruiken AS mag_gebruiken_69,
  intakes1.inkomen_overig AS inkomen_overig_70,
  intakes1.legitimatie_nummer AS legitimatie_nummer_71,
  intakes1.legitimatie_geldig_tot AS legitimatie_geldig_tot_72,
  intakes1.verblijf_in_NL_sinds AS verblijf_in_NL_sinds_73,
  intakes1.verblijf_in_amsterdam_sinds AS verblijf_in_amsterdam_sinds_74,
  intakes1.opmerking_andere_instanties AS opmerking_andere_instanties_75,
  intakes1.medische_achtergrond AS medische_achtergrond_76,
  intakes1.verwachting_dienstaanbod AS verwachting_dienstaanbod_77,
  intakes1.toekomstplannen AS toekomstplannen_78,
  intakes1.indruk AS indruk_79,
  intakes1.informele_zorg AS informele_zorg_80,
  intakes1.dagbesteding AS dagbesteding_81,
  intakes1.inloophuis AS inloophuis_82,
  intakes1.hulpverlening AS hulpverlening_83,
  intakes1.doelgroep AS doelgroep_84,
  intakes1.verslaving_overig AS verslaving_overig_85,
  intakes1.eerste_gebruik AS eerste_gebruik_86,
  intakes1.geinformeerd_opslaan_gegevens AS geinformeerd_opslaan_gegevens_87,
  intakes1.created AS created_88,
  intakes1.modified AS modified_89,
  intakes1.klant_id_before_constraint AS klant_id_before_constraint_90,
  intakes1.medewerker_id_before_constraint AS medewerker_id_before_constraint_91,
  intakes1.toegang_vrouwen_nacht_opvang AS toegang_vrouwen_nacht_opvang_92,
  intakes1.woonsituatie_id_before_constraint AS woonsituatie_id_before_constraint_93,
  locaties3.nachtopvang AS nachtopvang_94,
  locaties3.gebruikersruimte AS gebruikersruimte_95,
  locaties3.maatschappelijkwerk AS maatschappelijkwerk_96,
  locaties3.tbc_check AS tbc_check_97,
  locaties3.wachtlijst AS wachtlijst_98,
  locaties3.datum_van AS datum_van_99,
  locaties3.datum_tot AS datum_tot_100,
  locaties3.id AS id_101,
  locaties3.naam AS naam_102,
  locaties3.created AS created_103,
  locaties3.modified AS modified_104,
  klanten.werkgebied AS werkgebied_105,
  klanten.postcodegebied AS postcodegebied_106,
  klanten.first_intake_id AS first_intake_id_107,
  klanten.laste_intake_id AS laste_intake_id_108,
  klanten.huidigeStatus_id AS huidigeStatus_id_109,
  klanten.huidigeMwStatus_id AS huidigeMwStatus_id_110,
  klanten.mwBinnenViaOptieKlant_id AS mwBinnenViaOptieKlant_id_111,
  klanten.info_id AS info_id_112,
  klanten.laatste_registratie_id AS laatste_registratie_id_113,
  klanten.merged_id AS merged_id_114,
  klanten.partner_id AS partner_id_115,
  klanten.maatschappelijkWerker_id AS maatschappelijkWerker_id_116,
  klanten.medewerker_id AS medewerker_id_117,
  klanten.land_id AS land_id_118,
  klanten.nationaliteit_id AS nationaliteit_id_119,
  klanten.geslacht_id AS geslacht_id_120,
  intakes1.klant_id AS klant_id_121,
  intakes1.medewerker_id AS medewerker_id_122,
  intakes1.locatie2_id AS locatie2_id_123,
  intakes1.locatie1_id AS locatie1_id_124,
  intakes1.locatie3_id AS locatie3_id_125,
  intakes1.verblijfstatus_id AS verblijfstatus_id_126,
  intakes1.legitimatie_id AS legitimatie_id_127,
  intakes1.woonsituatie_id AS woonsituatie_id_128,
  intakes1.infobaliedoelgroep_id AS infobaliedoelgroep_id_129,
  intakes1.primaireProblematiek_id AS primaireProblematiek_id_130,
  intakes1.primaireproblematieksfrequentie_id AS primaireproblematieksfrequentie_id_131,
  intakes1.primaireproblematieksperiode_id AS primaireproblematieksperiode_id_132,
  intakes1.verslavingsfrequentie_id AS verslavingsfrequentie_id_133,
  intakes1.verslavingsperiode_id AS verslavingsperiode_id_134,
  intakes1.zrm_id AS zrm_id_135
FROM
  klanten klanten
  INNER JOIN mw_dossier_statussen mw_dossier_statussen2 ON (mw_dossier_statussen2.klant_id = klanten.id)
  AND mw_dossier_statussen2.class IN ('Aanmelding', 'Afsluiting')
  LEFT JOIN verslagen verslagen1 ON klanten.id = verslagen1.klant_id
  LEFT JOIN verslagen verslagen2 ON klanten.id = verslagen2.klant_id
  AND (
    verslagen1.klant_id = verslagen2.klant_id
    AND (verslagen1.datum < verslagen2.datum OR (verslagen1.datum = verslagen2.datum AND verslagen1.id < verslagen2.id))
  )
  LEFT JOIN mw_dossier_info info ON klanten.info_id = info.id
  LEFT JOIN intakes intakes1 ON klanten.laste_intake_id = intakes1.id
  LEFT JOIN locaties locaties1 ON intakes1.locatie2_id = locaties1.id
  LEFT JOIN locaties locaties2 ON verslagen1.locatie_id = locaties2.id
  LEFT JOIN mw_dossier_statussen mw_dossier_statussen ON klanten.huidigeMwStatus_id = mw_dossier_statussen.id
  AND mw_dossier_statussen.class IN ('Aanmelding', 'Afsluiting')
  LEFT JOIN locaties locaties3 ON intakes1.locatie1_id = locaties3.id
WHERE
  (verslagen2.id IS NULL)
  AND ((klanten.disabled IS NULL OR klanten.disabled = 0))
  AND (klanten.deleted IS NULL)
GROUP BY
  klanten.id
         */
        return $this->findAllNew($page, $filter);
        $builder
            // SELECTs
            ->addSelect('klant')
            ->addSelect('laatsteIntake')
            ->addSelect('gebruikersruimte')
            ->addSelect('verslag.datum AS datumLaatsteVerslag')
            ->addSelect('locatie.naam AS laatsteVerslagLocatie')
            ->addSelect('info.isGezin AS isGezin')
            ->addSelect('COUNT(DISTINCT verslag.id) AS aantalVerslagen')
            // JOINs
            ->join('MwBundle\Entity\MwDossierStatus', 'dossierstatus', 'WITH', 'dossierstatus.klant = klant')
            // LEFT JOSINs
            ->leftJoin($this->alias.'.verslagen', 'verslag')
            ->leftJoin($this->alias.'.verslagen', 'v2', 'WITH', 'verslag.klant = v2.klant AND (verslag.datum < v2.datum OR (verslag.datum = v2.datum AND verslag.id < v2.id))')
            ->leftJoin('klant.info', 'info')
            ->leftJoin($this->alias.'.laatsteIntake', 'laatsteIntake')
            ->leftJoin('laatsteIntake.intakelocatie', 'laatsteIntakeLocatie')
            ->leftJoin('verslag.locatie', 'locatie')
            ->leftJoin($this->alias.'.huidigeMwStatus', 'huidigeMwStatus')
            ->leftJoin('laatsteIntake.gebruikersruimte', 'gebruikersruimte')
            // CONDITIONs
            ->where('v2.id IS NULL')
            ->groupBy('klant.id')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }


        if ($page) {
            return $this->paginator->paginate($builder->getQuery(), $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function findAllNew($page = null, ?FilterInterface $filter = null)
    {
        /**
         * @var KlantFilter $filter
         */
        $builder = $this->repository->createQueryBuilder($this->alias);

        $builder
            // SELECTs
            
            ->addSelect('laatsteIntake')
            ->addSelect('gebruikersruimte')
            ->addSelect('verslag.datum AS datumLaatsteVerslag')
            ->addSelect('locatie.naam AS laatsteVerslagLocatie')
            ->addSelect('info.isGezin AS isGezin')
            ->addSelect('COUNT(DISTINCT verslag.id) AS aantalVerslagen')
            // JOINs
            ->join('MwBundle\Entity\MwDossierStatus', 'dossierstatus', 'WITH', 'dossierstatus.klant = klant')
            // LEFT JOSINs
            ->leftJoin($this->alias.'.verslagen', 'verslag')
            //->leftJoin($this->alias.'.verslagen', 'v2', 'WITH', 'verslag.klant = v2.klant AND (verslag.datum < v2.datum OR (verslag.datum = v2.datum AND verslag.id < v2.id))')
            ->leftJoin('klant.info', 'info')
            ->leftJoin($this->alias.'.laatsteIntake', 'laatsteIntake')
            ->leftJoin('laatsteIntake.intakelocatie', 'laatsteIntakeLocatie')
            ->leftJoin('verslag.locatie', 'locatie')
            ->leftJoin($this->alias.'.huidigeMwStatus', 'huidigeMwStatus')
            ->leftJoin('laatsteIntake.gebruikersruimte', 'gebruikersruimte')
            // JOINs For Sorting
            ->leftJoin($this->alias.'.geslacht', 'geslacht')
            ->leftJoin('huidigeMwStatus.project', 'project')
            // CONDITIONs
            //->where('v2.id IS NULL')
            ->groupBy('klant.id')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }
        
        if ($page) {
            return $this->paginator->paginate($builder->getQuery(), $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * @return false
     *
     * @deprecated
     */
    public function findAllAfsluitredenenAfgeslotenKlantenForLocaties($startdatum, $einddatum, $locaties = [])
    {
        // Gebruik MwDossierStatus om dit soort dingen op te vragen.
        return false;
    }

    /**
     * @return false
     *
     * @deprecated
     */
    public function findAllNieuweKlantenForLocaties($startdatum, $einddatum, $locaties = [])
    {
        // Gebruik MwDossierStatus om dit soort dingen op te vragen.
        return false;
    }

    public function countResultatenPerLocatie($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('klant')
            /*
             * SELECT DISTINCT (klanten.id), mw_dossier_statussen.class, mw_resultaten.naam FROM klanten LEFT JOIN verslagen ON verslagen.klant_id = klanten.id
             * LEFT JOIN mw_dossier_statussen ON mw_dossier_statussen.id = klanten.huidigeMwStatus_id
             * LEFT JOIN mw_afsluiting_resultaat ON mw_afsluiting_resultaat.afsluiting_id = mw_dossier_statussen.id
             * INNER JOIN mw_resultaten ON mw_resultaten.id = mw_afsluiting_resultaat.resultaat_id
             * WHERE verslagen.id IS NOT NULL
             * AND mw_dossier_statussen.class IN ('Afsluiting')
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
