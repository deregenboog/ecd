<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307134305 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mw_aanmeldingen (id INT NOT NULL, project_id INT DEFAULT NULL, binnenVia_id INT NOT NULL, INDEX IDX_7FAFAA70D8471945 (binnenVia_id), INDEX IDX_7FAFAA70166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_afsluitingen (id INT NOT NULL, reden_id INT DEFAULT NULL, land_id INT DEFAULT NULL, locatie_id INT DEFAULT NULL, toelichting LONGTEXT DEFAULT NULL, INDEX IDX_BEEABDFFD29703A5 (reden_id), INDEX IDX_BEEABDFF1994904A (land_id), INDEX IDX_BEEABDFF4947630C (locatie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_intakes_administratie (id INT NOT NULL, woningnet TINYINT(1) NOT NULL, woningnet_duur VARCHAR(255) DEFAULT NULL, verzekerd TINYINT(1) NOT NULL, digid TINYINT(1) NOT NULL, huisarts TINYINT(1) NOT NULL, huisarts_contactgegevens LONGTEXT DEFAULT NULL, digitaal_vaardig TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_intakes_algemeen (id INT NOT NULL, verblijfsstatus_id INT DEFAULT NULL, geinformeerd TINYINT(1) NOT NULL, verblijfsstatus_toelichting LONGTEXT NOT NULL, jvg VARCHAR(255) NOT NULL, jvg_toelichting LONGTEXT NOT NULL, INDEX IDX_A0DF85D550F00222 (verblijfsstatus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_intakes_algemeen_talen (intakealgemeen_id INT NOT NULL, taal_id INT NOT NULL, INDEX IDX_AC256AC05DBDCE60 (intakealgemeen_id), INDEX IDX_AC256AC0A41FDDD (taal_id), PRIMARY KEY(intakealgemeen_id, taal_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_intakes_algemeen_landen_rechthebbend (intakealgemeen_id INT NOT NULL, land_id INT NOT NULL, INDEX IDX_FA54ABD85DBDCE60 (intakealgemeen_id), INDEX IDX_FA54ABD81994904A (land_id), PRIMARY KEY(intakealgemeen_id, land_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_intakes_gezin (id INT NOT NULL, gezin TINYINT(1) NOT NULL, dreigende_dakloosheid TINYINT(1) NOT NULL, dreigende_dakloosheid_toelichting LONGTEXT DEFAULT NULL, mog TINYINT(1) NOT NULL, regiobinding TINYINT(1) NOT NULL, regiobinding_toelichting LONGTEXT DEFAULT NULL, okt TINYINT(1) NOT NULL, hulpverleningKinderen TINYINT(1) NOT NULL, kinderbijslag TINYINT(1) NOT NULL, kindgebondenBudget TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_intakes_huisvesting (id INT NOT NULL, dakloos_duur VARCHAR(255) DEFAULT NULL, dakloos_oorzaak VARCHAR(255) DEFAULT NULL, huisvesting VARCHAR(255) NOT NULL, inschrijfadres LONGTEXT DEFAULT NULL, regiobinding TINYINT(1) DEFAULT NULL, regiobinding_toelichting LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_intakes_inkomen (id INT NOT NULL, inkomsten TINYINT(1) NOT NULL, inkomsten_toelichting LONGTEXT DEFAULT NULL, hulp_bij_inkomen TINYINT(1) NOT NULL, hulp_bij_inkomen_toelichting LONGTEXT DEFAULT NULL, stadspas TINYINT(1) NOT NULL, schuldenproblematiek TINYINT(1) NOT NULL, schuldenproblematiek_toelichting LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_intakes_verwachting (id INT NOT NULL, verwachtingen LONGTEXT DEFAULT NULL, plannen LONGTEXT DEFAULT NULL, actiepunten LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_intakes_welzijn (id INT NOT NULL, sociaal_netwerk LONGTEXT DEFAULT NULL, sociaal_netwerk_bekend TINYINT(1) NOT NULL, sociaal_netwerk_contactgegevens LONGTEXT DEFAULT NULL, andere_instanties TINYINT(1) NOT NULL, andere_instanties_contactgegevens LONGTEXT DEFAULT NULL, dagstructuur TINYINT(1) NOT NULL, dagstructuur_toelichting LONGTEXT DEFAULT NULL, fysiek_in_orde TINYINT(1) NOT NULL, fysiek_toelichting LONGTEXT DEFAULT NULL, psychische_problemen TINYINT(1) NOT NULL, psychische_problemen_toelichting LONGTEXT DEFAULT NULL, verslaving LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', verslaving_toelichting LONGTEXT DEFAULT NULL, justitie TINYINT(1) NOT NULL, justitie_toelichting LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_klanten (id INT AUTO_INCREMENT NOT NULL, mw_dossierstatus_id INT DEFAULT NULL, klant_id INT NOT NULL, medewerker_id INT NOT NULL, marking VARCHAR(255) DEFAULT \'aangemaakt\' NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_B703B202A3B07EE3 (mw_dossierstatus_id), UNIQUE INDEX UNIQ_B703B2023C427B2F (klant_id), INDEX IDX_B703B2023D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_klant_dossierstatus (mw_klant_id INT NOT NULL, mw_dossierstatus_id INT NOT NULL, INDEX IDX_ED03146110D9DED3 (mw_klant_id), INDEX IDX_ED031461A3B07EE3 (mw_dossierstatus_id), PRIMARY KEY(mw_klant_id, mw_dossierstatus_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mw_aanmeldingen ADD CONSTRAINT FK_7FAFAA70D8471945 FOREIGN KEY (binnenVia_id) REFERENCES mw_binnen_via (id)');
        $this->addSql('ALTER TABLE mw_aanmeldingen ADD CONSTRAINT FK_7FAFAA70166D1F9C FOREIGN KEY (project_id) REFERENCES mw_projecten (id)');
        $this->addSql('ALTER TABLE mw_aanmeldingen ADD CONSTRAINT FK_7FAFAA70BF396750 FOREIGN KEY (id) REFERENCES mw_dossier_statussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_afsluitingen ADD CONSTRAINT FK_BEEABDFFD29703A5 FOREIGN KEY (reden_id) REFERENCES mw_afsluiting_redenen (id)');
        $this->addSql('ALTER TABLE mw_afsluitingen ADD CONSTRAINT FK_BEEABDFF1994904A FOREIGN KEY (land_id) REFERENCES landen (id)');
        $this->addSql('ALTER TABLE mw_afsluitingen ADD CONSTRAINT FK_BEEABDFF4947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE mw_afsluitingen ADD CONSTRAINT FK_BEEABDFFBF396750 FOREIGN KEY (id) REFERENCES mw_dossier_statussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_intakes_administratie ADD CONSTRAINT FK_990B46D2BF396750 FOREIGN KEY (id) REFERENCES mw_dossier_statussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_intakes_algemeen ADD CONSTRAINT FK_A0DF85D550F00222 FOREIGN KEY (verblijfsstatus_id) REFERENCES verblijfstatussen (id)');
        $this->addSql('ALTER TABLE mw_intakes_algemeen ADD CONSTRAINT FK_A0DF85D5BF396750 FOREIGN KEY (id) REFERENCES mw_dossier_statussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_intakes_algemeen_talen ADD CONSTRAINT FK_AC256AC05DBDCE60 FOREIGN KEY (intakealgemeen_id) REFERENCES mw_intakes_algemeen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_intakes_algemeen_talen ADD CONSTRAINT FK_AC256AC0A41FDDD FOREIGN KEY (taal_id) REFERENCES talen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_intakes_algemeen_landen_rechthebbend ADD CONSTRAINT FK_FA54ABD85DBDCE60 FOREIGN KEY (intakealgemeen_id) REFERENCES mw_intakes_algemeen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_intakes_algemeen_landen_rechthebbend ADD CONSTRAINT FK_FA54ABD81994904A FOREIGN KEY (land_id) REFERENCES landen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_intakes_gezin ADD CONSTRAINT FK_E7513A0BF396750 FOREIGN KEY (id) REFERENCES mw_dossier_statussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_intakes_huisvesting ADD CONSTRAINT FK_2C08A52BBF396750 FOREIGN KEY (id) REFERENCES mw_dossier_statussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_intakes_inkomen ADD CONSTRAINT FK_99B5E629BF396750 FOREIGN KEY (id) REFERENCES mw_dossier_statussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_intakes_verwachting ADD CONSTRAINT FK_3EE9FE96BF396750 FOREIGN KEY (id) REFERENCES mw_dossier_statussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_intakes_welzijn ADD CONSTRAINT FK_91E53ED7BF396750 FOREIGN KEY (id) REFERENCES mw_dossier_statussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_klanten ADD CONSTRAINT FK_B703B202A3B07EE3 FOREIGN KEY (mw_dossierstatus_id) REFERENCES mw_dossier_statussen (id)');
        $this->addSql('ALTER TABLE mw_klanten ADD CONSTRAINT FK_B703B2023C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE mw_klanten ADD CONSTRAINT FK_B703B2023D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE mw_klant_dossierstatus ADD CONSTRAINT FK_ED03146110D9DED3 FOREIGN KEY (mw_klant_id) REFERENCES mw_klanten (id)');
        $this->addSql('ALTER TABLE mw_klant_dossierstatus ADD CONSTRAINT FK_ED031461A3B07EE3 FOREIGN KEY (mw_dossierstatus_id) REFERENCES mw_dossier_statussen (id)');
        $this->addSql('ALTER TABLE mw_afsluiting_resultaat DROP FOREIGN KEY FK_EBA6C1A2ECDAD1A9');
        $this->addSql('ALTER TABLE mw_afsluiting_resultaat ADD CONSTRAINT FK_EBA6C1A2ECDAD1A9 FOREIGN KEY (afsluiting_id) REFERENCES mw_afsluitingen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_dossier_statussen DROP FOREIGN KEY FK_D74783BB166D1F9C');
        $this->addSql('ALTER TABLE mw_dossier_statussen DROP FOREIGN KEY FK_D74783BB1994904A');
        $this->addSql('ALTER TABLE mw_dossier_statussen DROP FOREIGN KEY FK_D74783BB305E4E53');
        $this->addSql('ALTER TABLE mw_dossier_statussen DROP FOREIGN KEY FK_D74783BB4947630C');
        $this->addSql('ALTER TABLE mw_dossier_statussen DROP FOREIGN KEY FK_D74783BBD29703A5');
        $this->addSql('ALTER TABLE mw_dossier_statussen DROP FOREIGN KEY FK_D74783BB3C427B2F');
        $this->addSql('DROP INDEX class ON mw_dossier_statussen');
        $this->addSql('DROP INDEX IDX_D74783BB166D1F9C ON mw_dossier_statussen');
        $this->addSql('DROP INDEX IDX_D74783BB1994904A ON mw_dossier_statussen');
        $this->addSql('DROP INDEX IDX_D74783BB305E4E53 ON mw_dossier_statussen');
        $this->addSql('DROP INDEX IDX_D74783BB4947630C ON mw_dossier_statussen');
        $this->addSql('DROP INDEX IDX_D74783BBD29703A5 ON mw_dossier_statussen');

        // migrate data
        $this->addSql('INSERT INTO mw_klanten (klant_id)
            SELECT DISTINCT id
            FROM klanten k
            JOIN verslagen v ON v.klant_id = k.id
        ');
        $this->addSql('INSERT INTO mw_klanten (klant_id)
            SELECT DISTINCT id
            FROM klanten k
            JOIN verslagen v ON v.klant_id = k.id
        ');

        $this->addSql('ALTER TABLE mw_dossier_statussen DROP reden_id, DROP land_id, DROP locatie_id, DROP project_id, DROP toelichting, DROP binnenViaOptieKlant_id, CHANGE medewerker_id medewerker_id INT NOT NULL, CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE modified modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE mw_dossier_statussen ADD CONSTRAINT FK_D74783BB3C427B2F FOREIGN KEY (klant_id) REFERENCES mw_klanten (id)');
        $this->addSql('ALTER TABLE mw_projecten ADD wachtlijst TINYINT(1) NOT NULL, CHANGE active `active` TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE mw_vrijwilligers ADD locatie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mw_vrijwilligers ADD CONSTRAINT FK_CFC2BAE34947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id)');
        $this->addSql('CREATE INDEX IDX_CFC2BAE34947630C ON mw_vrijwilligers (locatie_id)');
        $this->addSql('ALTER TABLE verslagen DROP FOREIGN KEY FK_2BBABA713C427B2F');
        $this->addSql('ALTER TABLE verslagen ADD CONSTRAINT FK_2BBABA713C427B2F FOREIGN KEY (klant_id) REFERENCES mw_klanten (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mw_afsluiting_resultaat DROP FOREIGN KEY FK_EBA6C1A2ECDAD1A9');
        $this->addSql('ALTER TABLE mw_intakes_algemeen_talen DROP FOREIGN KEY FK_AC256AC05DBDCE60');
        $this->addSql('ALTER TABLE mw_intakes_algemeen_landen_rechthebbend DROP FOREIGN KEY FK_FA54ABD85DBDCE60');
        $this->addSql('ALTER TABLE mw_dossier_statussen DROP FOREIGN KEY FK_D74783BB3C427B2F');
        $this->addSql('ALTER TABLE mw_klant_dossierstatus DROP FOREIGN KEY FK_ED03146110D9DED3');
        $this->addSql('ALTER TABLE verslagen DROP FOREIGN KEY FK_2BBABA713C427B2F');
        $this->addSql('DROP TABLE mw_aanmeldingen');
        $this->addSql('DROP TABLE mw_afsluitingen');
        $this->addSql('DROP TABLE mw_intakes_administratie');
        $this->addSql('DROP TABLE mw_intakes_algemeen');
        $this->addSql('DROP TABLE mw_intakes_algemeen_talen');
        $this->addSql('DROP TABLE mw_intakes_algemeen_landen_rechthebbend');
        $this->addSql('DROP TABLE mw_intakes_gezin');
        $this->addSql('DROP TABLE mw_intakes_huisvesting');
        $this->addSql('DROP TABLE mw_intakes_inkomen');
        $this->addSql('DROP TABLE mw_intakes_verwachting');
        $this->addSql('DROP TABLE mw_intakes_welzijn');
        $this->addSql('DROP TABLE mw_klanten');
        $this->addSql('DROP TABLE mw_klant_dossierstatus');
        $this->addSql('ALTER TABLE mw_afsluiting_resultaat DROP FOREIGN KEY FK_EBA6C1A2ECDAD1A9');
        $this->addSql('ALTER TABLE mw_afsluiting_resultaat ADD CONSTRAINT FK_EBA6C1A2ECDAD1A9 FOREIGN KEY (afsluiting_id) REFERENCES mw_dossier_statussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_dossier_statussen DROP FOREIGN KEY FK_D74783BB3C427B2F');
        $this->addSql('ALTER TABLE mw_dossier_statussen ADD reden_id INT DEFAULT NULL, ADD land_id INT DEFAULT NULL, ADD locatie_id INT DEFAULT NULL, ADD project_id INT DEFAULT NULL, ADD toelichting LONGTEXT CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, ADD binnenViaOptieKlant_id INT DEFAULT 0 NOT NULL, CHANGE medewerker_id medewerker_id INT DEFAULT NULL, CHANGE created created DATETIME NOT NULL, CHANGE modified modified DATETIME NOT NULL');
        $this->addSql('ALTER TABLE mw_dossier_statussen ADD CONSTRAINT FK_D74783BB166D1F9C FOREIGN KEY (project_id) REFERENCES mw_projecten (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE mw_dossier_statussen ADD CONSTRAINT FK_D74783BB1994904A FOREIGN KEY (land_id) REFERENCES landen (id)');
        $this->addSql('ALTER TABLE mw_dossier_statussen ADD CONSTRAINT FK_D74783BB305E4E53 FOREIGN KEY (binnenViaOptieKlant_id) REFERENCES mw_binnen_via (id)');
        $this->addSql('ALTER TABLE mw_dossier_statussen ADD CONSTRAINT FK_D74783BB4947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE mw_dossier_statussen ADD CONSTRAINT FK_D74783BBD29703A5 FOREIGN KEY (reden_id) REFERENCES mw_afsluiting_redenen (id)');
        $this->addSql('ALTER TABLE mw_dossier_statussen ADD CONSTRAINT FK_D74783BB3C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('CREATE INDEX class ON mw_dossier_statussen (class, id, klant_id)');
        $this->addSql('CREATE INDEX IDX_D74783BB166D1F9C ON mw_dossier_statussen (project_id)');
        $this->addSql('CREATE INDEX IDX_D74783BB1994904A ON mw_dossier_statussen (land_id)');
        $this->addSql('CREATE INDEX IDX_D74783BB305E4E53 ON mw_dossier_statussen (binnenViaOptieKlant_id)');
        $this->addSql('CREATE INDEX IDX_D74783BB4947630C ON mw_dossier_statussen (locatie_id)');
        $this->addSql('CREATE INDEX IDX_D74783BBD29703A5 ON mw_dossier_statussen (reden_id)');
        $this->addSql('ALTER TABLE mw_projecten DROP wachtlijst, CHANGE `active` active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE mw_vrijwilligers DROP FOREIGN KEY FK_CFC2BAE34947630C');
        $this->addSql('DROP INDEX IDX_CFC2BAE34947630C ON mw_vrijwilligers');
        $this->addSql('ALTER TABLE mw_vrijwilligers DROP locatie_id');
    }
}
