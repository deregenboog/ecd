<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220616092125 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE oekraine_documenten (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, naam VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_7DB476213D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_inkomens (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, datum_van DATE NOT NULL, datum_tot DATE NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_registraties_recent (registratie_id INT NOT NULL, locatie_id INT DEFAULT NULL, klant_id INT DEFAULT NULL, max_buiten DATETIME NOT NULL, INDEX IDX_8C35B27E4947630C (locatie_id), INDEX IDX_8C35B27E3C427B2F (klant_id), PRIMARY KEY(registratie_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_locaties (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, datum_van DATE NOT NULL, datum_tot DATE DEFAULT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_dossier_statussen (id INT AUTO_INCREMENT NOT NULL, bezoeker_id INT NOT NULL, medewerker_id INT DEFAULT NULL, reden_id INT DEFAULT NULL, land_id INT DEFAULT NULL, datum DATE NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, class VARCHAR(255) NOT NULL, toelichting LONGTEXT DEFAULT NULL, INDEX IDX_19DBBEBC8AEEBAAE (bezoeker_id), INDEX IDX_19DBBEBC3D707F64 (medewerker_id), INDEX IDX_19DBBEBCD29703A5 (reden_id), INDEX IDX_19DBBEBC1994904A (land_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_afsluiting_redenen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, actief TINYINT(1) NOT NULL, gewicht INT NOT NULL, land TINYINT(1) NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_toegang (bezoeker_id INT NOT NULL, locatie_id INT NOT NULL, INDEX IDX_894DD3E68AEEBAAE (bezoeker_id), INDEX IDX_894DD3E64947630C (locatie_id), PRIMARY KEY(bezoeker_id, locatie_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_bezoekers (id INT AUTO_INCREMENT NOT NULL, intake_id INT NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, appKlant_id INT NOT NULL, dossierStatus_id INT NOT NULL, UNIQUE INDEX UNIQ_7027BCA15C217849 (appKlant_id), UNIQUE INDEX UNIQ_7027BCA1EF862509 (dossierStatus_id), UNIQUE INDEX UNIQ_7027BCA1733DE450 (intake_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_registraties (id INT AUTO_INCREMENT NOT NULL, locatie_id INT DEFAULT NULL, klant_id INT DEFAULT NULL, binnen DATETIME NOT NULL, binnen_date DATE DEFAULT NULL, buiten DATETIME DEFAULT NULL, douche INT NOT NULL, mw INT NOT NULL, gbrv INT NOT NULL, kleding TINYINT(1) NOT NULL, maaltijd TINYINT(1) NOT NULL, activering TINYINT(1) NOT NULL, veegploeg TINYINT(1) NOT NULL, aantalSpuiten INT DEFAULT NULL, closed TINYINT(1) DEFAULT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_7E55584C4947630C (locatie_id), INDEX IDX_7E55584C3C427B2F (klant_id), INDEX IDX_7E55584C4947630C4C74DE4C5E4E404 (locatie_id, closed, binnen_date), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_afsluitredenen_vrijwilligers (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_incidenten (id INT AUTO_INCREMENT NOT NULL, locatie_id INT DEFAULT NULL, klant_id INT NOT NULL, datum DATE NOT NULL, remark LONGTEXT DEFAULT NULL, politie TINYINT(1) NOT NULL, ambulance TINYINT(1) NOT NULL, crisisdienst TINYINT(1) NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_18404EA04947630C (locatie_id), INDEX IDX_18404EA03C427B2F (klant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_intakes (id INT AUTO_INCREMENT NOT NULL, bezoeker_id INT DEFAULT NULL, medewerker_id INT DEFAULT NULL, woonlocatie_id INT DEFAULT NULL, verblijfstatus_id INT DEFAULT NULL, legitimatie_id INT DEFAULT NULL, kamernummer VARCHAR(255) DEFAULT NULL, datum_intake DATE DEFAULT NULL, postadres VARCHAR(255) DEFAULT NULL, postcode VARCHAR(255) DEFAULT NULL, woonplaats VARCHAR(255) DEFAULT NULL, telefoonnummer VARCHAR(255) DEFAULT NULL, mag_gebruiken TINYINT(1) DEFAULT NULL, inkomen_overig VARCHAR(255) DEFAULT NULL, legitimatie_nummer VARCHAR(255) DEFAULT NULL, legitimatie_geldig_tot DATE DEFAULT NULL, verblijf_in_NL_sinds DATE DEFAULT NULL, verblijf_in_amsterdam_sinds DATE DEFAULT NULL, opmerking_andere_instanties VARCHAR(255) DEFAULT NULL, medische_achtergrond VARCHAR(255) DEFAULT NULL, toekomstplannen VARCHAR(255) DEFAULT NULL, indruk VARCHAR(255) DEFAULT NULL, informele_zorg VARCHAR(255) DEFAULT NULL, werkhulp VARCHAR(255) NOT NULL, hulpverlening VARCHAR(255) NOT NULL, geinformeerd_opslaan_gegevens TINYINT(1) NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_C84A94C38AEEBAAE (bezoeker_id), INDEX IDX_C84A94C33D707F64 (medewerker_id), INDEX IDX_C84A94C385332A14 (woonlocatie_id), INDEX IDX_C84A94C348D0634A (verblijfstatus_id), INDEX IDX_C84A94C3EB38826A (legitimatie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_inkomens_intakes (intake_id INT NOT NULL, inkomen_id INT NOT NULL, INDEX IDX_55D038FE733DE450 (intake_id), INDEX IDX_55D038FEDE7E5B0 (inkomen_id), PRIMARY KEY(intake_id, inkomen_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_intakes_instanties (intake_id INT NOT NULL, instantie_id INT NOT NULL, INDEX IDX_86E955E3733DE450 (intake_id), INDEX IDX_86E955E32A1C57EF (instantie_id), PRIMARY KEY(intake_id, instantie_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE oekraine_documenten ADD CONSTRAINT FK_7DB476213D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE oekraine_registraties_recent ADD CONSTRAINT FK_8C35B27E5CD9765E FOREIGN KEY (registratie_id) REFERENCES oekraine_registraties (id)');
        $this->addSql('ALTER TABLE oekraine_registraties_recent ADD CONSTRAINT FK_8C35B27E4947630C FOREIGN KEY (locatie_id) REFERENCES oekraine_locaties (id)');
        $this->addSql('ALTER TABLE oekraine_registraties_recent ADD CONSTRAINT FK_8C35B27E3C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE oekraine_dossier_statussen ADD CONSTRAINT FK_19DBBEBC8AEEBAAE FOREIGN KEY (bezoeker_id) REFERENCES oekraine_bezoekers (id)');
        $this->addSql('ALTER TABLE oekraine_dossier_statussen ADD CONSTRAINT FK_19DBBEBC3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE oekraine_dossier_statussen ADD CONSTRAINT FK_19DBBEBCD29703A5 FOREIGN KEY (reden_id) REFERENCES oekraine_afsluiting_redenen (id)');
        $this->addSql('ALTER TABLE oekraine_dossier_statussen ADD CONSTRAINT FK_19DBBEBC1994904A FOREIGN KEY (land_id) REFERENCES landen (id)');
        $this->addSql('ALTER TABLE oekraine_toegang ADD CONSTRAINT FK_894DD3E68AEEBAAE FOREIGN KEY (bezoeker_id) REFERENCES oekraine_bezoekers (id)');
        $this->addSql('ALTER TABLE oekraine_toegang ADD CONSTRAINT FK_894DD3E64947630C FOREIGN KEY (locatie_id) REFERENCES oekraine_locaties (id)');
        $this->addSql('ALTER TABLE oekraine_bezoekers ADD CONSTRAINT FK_7027BCA15C217849 FOREIGN KEY (appKlant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE oekraine_bezoekers ADD CONSTRAINT FK_7027BCA1EF862509 FOREIGN KEY (dossierStatus_id) REFERENCES oekraine_dossier_statussen (id)');
        $this->addSql('ALTER TABLE oekraine_bezoekers ADD CONSTRAINT FK_7027BCA1733DE450 FOREIGN KEY (intake_id) REFERENCES oekraine_intakes (id)');
        $this->addSql('ALTER TABLE oekraine_registraties ADD CONSTRAINT FK_7E55584C4947630C FOREIGN KEY (locatie_id) REFERENCES oekraine_locaties (id)');
        $this->addSql('ALTER TABLE oekraine_registraties ADD CONSTRAINT FK_7E55584C3C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE oekraine_incidenten ADD CONSTRAINT FK_18404EA04947630C FOREIGN KEY (locatie_id) REFERENCES oekraine_locaties (id)');
        $this->addSql('ALTER TABLE oekraine_incidenten ADD CONSTRAINT FK_18404EA03C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE oekraine_intakes ADD CONSTRAINT FK_C84A94C38AEEBAAE FOREIGN KEY (bezoeker_id) REFERENCES oekraine_bezoekers (id)');
        $this->addSql('ALTER TABLE oekraine_intakes ADD CONSTRAINT FK_C84A94C33D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE oekraine_intakes ADD CONSTRAINT FK_C84A94C385332A14 FOREIGN KEY (woonlocatie_id) REFERENCES oekraine_locaties (id)');
        $this->addSql('ALTER TABLE oekraine_intakes ADD CONSTRAINT FK_C84A94C348D0634A FOREIGN KEY (verblijfstatus_id) REFERENCES verblijfstatussen (id)');
        $this->addSql('ALTER TABLE oekraine_intakes ADD CONSTRAINT FK_C84A94C3EB38826A FOREIGN KEY (legitimatie_id) REFERENCES legitimaties (id)');
        $this->addSql('ALTER TABLE oekraine_inkomens_intakes ADD CONSTRAINT FK_55D038FE733DE450 FOREIGN KEY (intake_id) REFERENCES oekraine_intakes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oekraine_inkomens_intakes ADD CONSTRAINT FK_55D038FEDE7E5B0 FOREIGN KEY (inkomen_id) REFERENCES oekraine_inkomens (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oekraine_intakes_instanties ADD CONSTRAINT FK_86E955E3733DE450 FOREIGN KEY (intake_id) REFERENCES oekraine_intakes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oekraine_intakes_instanties ADD CONSTRAINT FK_86E955E32A1C57EF FOREIGN KEY (instantie_id) REFERENCES instanties (id) ON DELETE CASCADE');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE oekraine_inkomens_intakes DROP FOREIGN KEY FK_55D038FEDE7E5B0');
        $this->addSql('ALTER TABLE oekraine_registraties_recent DROP FOREIGN KEY FK_8C35B27E4947630C');
        $this->addSql('ALTER TABLE oekraine_toegang DROP FOREIGN KEY FK_894DD3E64947630C');
        $this->addSql('ALTER TABLE oekraine_registraties DROP FOREIGN KEY FK_7E55584C4947630C');
        $this->addSql('ALTER TABLE oekraine_incidenten DROP FOREIGN KEY FK_18404EA04947630C');
        $this->addSql('ALTER TABLE oekraine_intakes DROP FOREIGN KEY FK_C84A94C385332A14');
        $this->addSql('ALTER TABLE oekraine_bezoekers DROP FOREIGN KEY FK_7027BCA1EF862509');
        $this->addSql('ALTER TABLE oekraine_dossier_statussen DROP FOREIGN KEY FK_19DBBEBCD29703A5');
        $this->addSql('ALTER TABLE oekraine_dossier_statussen DROP FOREIGN KEY FK_19DBBEBC8AEEBAAE');
        $this->addSql('ALTER TABLE oekraine_toegang DROP FOREIGN KEY FK_894DD3E68AEEBAAE');
        $this->addSql('ALTER TABLE oekraine_intakes DROP FOREIGN KEY FK_C84A94C38AEEBAAE');
        $this->addSql('ALTER TABLE oekraine_registraties_recent DROP FOREIGN KEY FK_8C35B27E5CD9765E');
        $this->addSql('ALTER TABLE oekraine_bezoekers DROP FOREIGN KEY FK_7027BCA1733DE450');
        $this->addSql('ALTER TABLE oekraine_inkomens_intakes DROP FOREIGN KEY FK_55D038FE733DE450');
        $this->addSql('ALTER TABLE oekraine_intakes_instanties DROP FOREIGN KEY FK_86E955E3733DE450');

        $this->addSql('DROP TABLE oekraine_documenten');
        $this->addSql('DROP TABLE oekraine_inkomens');
        $this->addSql('DROP TABLE oekraine_registraties_recent');
        $this->addSql('DROP TABLE oekraine_locaties');
        $this->addSql('DROP TABLE oekraine_dossier_statussen');
        $this->addSql('DROP TABLE oekraine_afsluiting_redenen');
        $this->addSql('DROP TABLE oekraine_toegang');
        $this->addSql('DROP TABLE oekraine_bezoekers');
        $this->addSql('DROP TABLE oekraine_registraties');
        $this->addSql('DROP TABLE oekraine_afsluitredenen_vrijwilligers');
        $this->addSql('DROP TABLE oekraine_incidenten');
        $this->addSql('DROP TABLE oekraine_intakes');
        $this->addSql('DROP TABLE oekraine_inkomens_intakes');
        $this->addSql('DROP TABLE oekraine_intakes_instanties');

    }
}
