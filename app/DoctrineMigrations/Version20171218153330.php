<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171218153330 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $tables = [
            'clip_behandelaars',
            'clip_client_document',
            'clip_clienten',
            'clip_communicatiekanalen',
            'clip_contactmomenten',
            'clip_documenten',
            'clip_hulpvragersoorten',
            'clip_leeftijdscategorieen',
            'clip_viacategorieen',
            'clip_vraag_document',
            'clip_vraagsoorten',
            'clip_vragen',
            'dagbesteding_afsluitingen',
            'dagbesteding_contactpersonen',
            'dagbesteding_dagdelen',
            'dagbesteding_deelnemer_document',
            'dagbesteding_deelnemer_verslag',
            'dagbesteding_deelnemers',
            'dagbesteding_documenten',
            'dagbesteding_locaties',
            'dagbesteding_projecten',
            'dagbesteding_rapportage_document',
            'dagbesteding_rapportages',
            'dagbesteding_resultaatgebieden',
            'dagbesteding_resultaatgebiedsoorten',
            'dagbesteding_traject_document',
            'dagbesteding_traject_locatie',
            'dagbesteding_traject_project',
            'dagbesteding_traject_verslag',
            'dagbesteding_trajectbegeleiders',
            'dagbesteding_trajecten',
            'dagbesteding_trajectsoorten',
            'dagbesteding_verslagen',
            'ext_log_entries',
            'ggw_gebieden',
            'hs_activiteiten',
            'hs_arbeiders',
            'hs_betalingen',
            'hs_declaratie_categorieen',
            'hs_declaratie_document',
            'hs_declaraties',
            'hs_dienstverlener_document',
            'hs_dienstverlener_memo',
            'hs_dienstverleners',
            'hs_documenten',
            'hs_facturen',
            'hs_factuur_klus',
            'hs_herinneringen',
            'hs_klant_document',
            'hs_klant_memo',
            'hs_klanten',
            'hs_klus_dienstverlener',
            'hs_klus_memo',
            'hs_klus_vrijwilliger',
            'hs_klussen',
            'hs_memos',
            'hs_registraties',
            'hs_vrijwilliger_document',
            'hs_vrijwilliger_memo',
            'hs_vrijwilligers',
            'inloop_afsluiting_redenen',
            'inloop_dossier_statussen',
            'iz_doelgroepen',
            'iz_doelstellingen',
            'iz_hulpvraagsoorten',
            'iz_matching_klanten',
            'iz_matching_vrijwilligers',
            'iz_matchingklant_doelgroep',
            'iz_matchingvrijwilliger_doelgroep',
            'iz_matchingvrijwilliger_hulpvraagsoort',
            'odp_afsluitingen',
            'odp_coordinatoren',
            'odp_deelnemer_document',
            'odp_deelnemer_verslag',
            'odp_deelnemers',
            'odp_documenten',
            'odp_huuraanbiedingen',
            'odp_huuraanbod_verslag',
            'odp_huurovereenkomst_document',
            'odp_huurovereenkomst_verslag',
            'odp_huurovereenkomsten',
            'odp_huurverzoek_verslag',
            'odp_huurverzoeken',
            'odp_intakes',
            'odp_verslagen',
            'odp_woningbouwcorporaties',
            'oek_deelname_statussen',
            'oek_deelnames',
            'oek_dossier_statussen',
            'oek_groepen',
            'oek_klanten',
            'oek_lidmaatschappen',
            'oek_trainingen',
            'oek_verwijzingen',
            'oekklant_oekdossierstatus',
            'postcodes',
            'schorsing_locatie',
            'werkgebieden',
            'zrm_v2_reports',
            'zrm_v2_settings',
        ];

        $this->addSql('SET foreign_key_checks = 0');
        foreach ($tables as $table) {
            $this->addSql("ALTER TABLE {$table} CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci");
        }
        $this->addSql('SET foreign_key_checks = 1');

        $this->addSql("INSERT INTO werkgebieden (naam) VALUES ('Amstelveen')");
        $this->addSql("INSERT INTO werkgebieden (naam) VALUES ('Overig')");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
