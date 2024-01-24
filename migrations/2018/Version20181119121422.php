<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181119121422 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ga_groepen (id INT AUTO_INCREMENT NOT NULL, werkgebied VARCHAR(255) DEFAULT NULL, naam VARCHAR(100) NOT NULL, activiteitenRegistreren TINYINT(1) NOT NULL, startdatum DATE NOT NULL, einddatum DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, discr VARCHAR(255) NOT NULL, INDEX IDX_EEBF811346708ED5 (werkgebied), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ga_dossiers (id INT AUTO_INCREMENT NOT NULL, afsluitreden_id INT DEFAULT NULL, klant_id INT DEFAULT NULL, vrijwilliger_id INT DEFAULT NULL, aanmelddatum DATE NOT NULL, afsluitdatum DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, discr VARCHAR(255) NOT NULL, INDEX IDX_470A2E33CA12F7AE (afsluitreden_id), INDEX IDX_470A2E333C427B2F (klant_id), INDEX IDX_470A2E3376B43BDC (vrijwilliger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ga_dossier_document (dossier_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_63244AA1611C0C56 (dossier_id), UNIQUE INDEX UNIQ_63244AA1C33F7837 (document_id), PRIMARY KEY(dossier_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ga_redenen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(100) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ga_verslagen (id INT AUTO_INCREMENT NOT NULL, dossier_id INT DEFAULT NULL, medewerker_id INT DEFAULT NULL, opmerking LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_33E9790A611C0C56 (dossier_id), INDEX IDX_33E9790A3D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ga_dossierafsluitredenen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ga_activiteitannuleringsredenen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ga_memos (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, datum DATETIME NOT NULL, onderwerp VARCHAR(255) NOT NULL, memo LONGTEXT NOT NULL, intake TINYINT(1) NOT NULL, INDEX IDX_692930E83D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ga_deelnames (id INT AUTO_INCREMENT NOT NULL, activiteit_id INT NOT NULL, dossier_id INT NOT NULL, status VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_F577BB9C5A8A0A1 (activiteit_id), INDEX IDX_F577BB9C611C0C56 (dossier_id), UNIQUE INDEX unique_activiteit_dossier_idx (activiteit_id, dossier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ga_activiteiten (id INT AUTO_INCREMENT NOT NULL, groep_id INT DEFAULT NULL, annuleringsreden_id INT DEFAULT NULL, naam VARCHAR(255) NOT NULL, datum DATETIME DEFAULT NULL, afgesloten TINYINT(1) DEFAULT NULL, aantalAnoniemeDeelnemers VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_56418A359EB44EC5 (groep_id), INDEX IDX_56418A35209ADBBB (annuleringsreden_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ga_intakes (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, dossier_id INT DEFAULT NULL, gespreksverslag LONGTEXT DEFAULT NULL, intakedatum DATE DEFAULT NULL, ondernemen TINYINT(1) DEFAULT NULL, overdag TINYINT(1) DEFAULT NULL, ontmoeten TINYINT(1) DEFAULT NULL, regelzaken TINYINT(1) DEFAULT NULL, informele_zorg TINYINT(1) DEFAULT NULL, dagbesteding TINYINT(1) DEFAULT NULL, inloophuis TINYINT(1) DEFAULT NULL, hulpverlening TINYINT(1) DEFAULT NULL, gezin_met_kinderen TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_21B329223D707F64 (medewerker_id), UNIQUE INDEX UNIQ_21B32922611C0C56 (dossier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ga_intake_zrm (intake_id INT NOT NULL, zrm_id INT NOT NULL, UNIQUE INDEX UNIQ_4F2ECDF1733DE450 (intake_id), UNIQUE INDEX UNIQ_4F2ECDF1C8250F57 (zrm_id), PRIMARY KEY(intake_id, zrm_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ga_lidmaatschappen (id INT AUTO_INCREMENT NOT NULL, groep_id INT NOT NULL, dossier_id INT NOT NULL, groepsactiviteiten_reden_id INT DEFAULT NULL, startdatum DATE DEFAULT NULL, einddatum DATE DEFAULT NULL, communicatieEmail TINYINT(1) DEFAULT NULL, communicatieTelefoon TINYINT(1) DEFAULT NULL, communicatiePost TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_D9F3C3F59EB44EC5 (groep_id), INDEX IDX_D9F3C3F5611C0C56 (dossier_id), INDEX IDX_D9F3C3F5248D162C (groepsactiviteiten_reden_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ga_groepen ADD CONSTRAINT FK_EEBF811346708ED5 FOREIGN KEY (werkgebied) REFERENCES werkgebieden (naam)');
        $this->addSql('ALTER TABLE ga_dossiers ADD CONSTRAINT FK_470A2E33CA12F7AE FOREIGN KEY (afsluitreden_id) REFERENCES ga_dossierafsluitredenen (id)');
        $this->addSql('ALTER TABLE ga_dossiers ADD CONSTRAINT FK_470A2E333C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE ga_dossiers ADD CONSTRAINT FK_470A2E3376B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES vrijwilligers (id)');
        $this->addSql('ALTER TABLE ga_dossier_document ADD CONSTRAINT FK_63244AA1611C0C56 FOREIGN KEY (dossier_id) REFERENCES ga_dossiers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ga_dossier_document ADD CONSTRAINT FK_63244AA1C33F7837 FOREIGN KEY (document_id) REFERENCES ga_documenten (id)');
        $this->addSql('ALTER TABLE ga_verslagen ADD CONSTRAINT FK_33E9790A611C0C56 FOREIGN KEY (dossier_id) REFERENCES ga_dossiers (id)');
        $this->addSql('ALTER TABLE ga_verslagen ADD CONSTRAINT FK_33E9790A3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE ga_memos ADD CONSTRAINT FK_692930E83D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE ga_deelnames ADD CONSTRAINT FK_F577BB9C5A8A0A1 FOREIGN KEY (activiteit_id) REFERENCES ga_activiteiten (id)');
        $this->addSql('ALTER TABLE ga_deelnames ADD CONSTRAINT FK_F577BB9C611C0C56 FOREIGN KEY (dossier_id) REFERENCES ga_dossiers (id)');
        $this->addSql('ALTER TABLE ga_activiteiten ADD CONSTRAINT FK_56418A359EB44EC5 FOREIGN KEY (groep_id) REFERENCES ga_groepen (id)');
        $this->addSql('ALTER TABLE ga_activiteiten ADD CONSTRAINT FK_56418A35209ADBBB FOREIGN KEY (annuleringsreden_id) REFERENCES ga_activiteitannuleringsredenen (id)');
        $this->addSql('ALTER TABLE ga_intakes ADD CONSTRAINT FK_21B329223D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE ga_intakes ADD CONSTRAINT FK_21B32922611C0C56 FOREIGN KEY (dossier_id) REFERENCES ga_dossiers (id)');
        $this->addSql('ALTER TABLE ga_intake_zrm ADD CONSTRAINT FK_4F2ECDF1733DE450 FOREIGN KEY (intake_id) REFERENCES ga_intakes (id)');
        $this->addSql('ALTER TABLE ga_intake_zrm ADD CONSTRAINT FK_4F2ECDF1C8250F57 FOREIGN KEY (zrm_id) REFERENCES zrm_reports (id)');
        $this->addSql('ALTER TABLE ga_lidmaatschappen ADD CONSTRAINT FK_D9F3C3F59EB44EC5 FOREIGN KEY (groep_id) REFERENCES ga_groepen (id)');
        $this->addSql('ALTER TABLE ga_lidmaatschappen ADD CONSTRAINT FK_D9F3C3F5611C0C56 FOREIGN KEY (dossier_id) REFERENCES ga_dossiers (id)');
        $this->addSql('ALTER TABLE ga_lidmaatschappen ADD CONSTRAINT FK_D9F3C3F5248D162C FOREIGN KEY (groepsactiviteiten_reden_id) REFERENCES groepsactiviteiten_redenen (id)');
        $this->addSql('ALTER TABLE ga_documenten DROP FOREIGN KEY FK_409E561276B43BDC');
        $this->addSql('DROP INDEX IDX_409E561276B43BDC ON ga_documenten');
        $this->addSql('ALTER TABLE ga_documenten DROP vrijwilliger_id');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
