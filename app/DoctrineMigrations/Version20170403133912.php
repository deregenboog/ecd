<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170403133912 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE oek_dossier_statussen (id INT AUTO_INCREMENT NOT NULL, verwijzing_id INT NOT NULL, medewerker_id INT NOT NULL, datum DATE NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, oekKlant_id INT NOT NULL, class VARCHAR(255) NOT NULL, INDEX IDX_D8FAC765E145C54F (oekKlant_id), INDEX IDX_D8FAC765D8B4CBDF (verwijzing_id), INDEX IDX_D8FAC7653D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oek_verwijzingen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, actief TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, class VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oek_deelnames (id INT AUTO_INCREMENT NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, oekTraining_id INT NOT NULL, oekKlant_id INT NOT NULL, oekDeelnameStatus_id INT DEFAULT NULL, INDEX IDX_A6C1F201120845B9 (oekTraining_id), INDEX IDX_A6C1F201E145C54F (oekKlant_id), UNIQUE INDEX UNIQ_A6C1F2014DF034FD (oekDeelnameStatus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oek_groepen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oek_trainingen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, startdatum DATE NOT NULL, starttijd TIME NOT NULL, einddatum DATE DEFAULT NULL, locatie VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, oekGroep_id INT NOT NULL, INDEX IDX_B0D582D43B3F0A5 (oekGroep_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oek_deelname_statussen (id INT AUTO_INCREMENT NOT NULL, datum DATE NOT NULL, status VARCHAR(255) NOT NULL, oekDeelname_id INT NOT NULL, INDEX IDX_4CBB9BCD6D7A74BD (oekDeelname_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oek_klanten (id INT AUTO_INCREMENT NOT NULL, klant_id INT NOT NULL, medewerker_id INT NOT NULL, voedselbankklant TINYINT(1) NOT NULL, opmerking LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, oekDossierStatus_id INT DEFAULT NULL, oekAanmelding_id INT DEFAULT NULL, oekAfsluiting_id INT DEFAULT NULL, INDEX IDX_A501F8F723473A1F (oekDossierStatus_id), INDEX IDX_A501F8F7C45AE93C (oekAanmelding_id), INDEX IDX_A501F8F7B99C329A (oekAfsluiting_id), UNIQUE INDEX UNIQ_A501F8F73C427B2F (klant_id), INDEX IDX_A501F8F73D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekklant_oekdossierstatus (oekklant_id INT NOT NULL, oekdossierstatus_id INT NOT NULL, INDEX IDX_1EF9C0A61833A719 (oekklant_id), INDEX IDX_1EF9C0A6B689C3C1 (oekdossierstatus_id), PRIMARY KEY(oekklant_id, oekdossierstatus_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oek_lidmaatschappen (id INT AUTO_INCREMENT NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, oekGroep_id INT NOT NULL, oekKlant_id INT NOT NULL, INDEX IDX_7B0B7DFF43B3F0A5 (oekGroep_id), INDEX IDX_7B0B7DFFE145C54F (oekKlant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE oek_dossier_statussen ADD CONSTRAINT FK_D8FAC765E145C54F FOREIGN KEY (oekKlant_id) REFERENCES oek_klanten (id)');
        $this->addSql('ALTER TABLE oek_dossier_statussen ADD CONSTRAINT FK_D8FAC765D8B4CBDF FOREIGN KEY (verwijzing_id) REFERENCES oek_verwijzingen (id)');
        $this->addSql('ALTER TABLE oek_dossier_statussen ADD CONSTRAINT FK_D8FAC7653D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE oek_deelnames ADD CONSTRAINT FK_A6C1F201120845B9 FOREIGN KEY (oekTraining_id) REFERENCES oek_trainingen (id)');
        $this->addSql('ALTER TABLE oek_deelnames ADD CONSTRAINT FK_A6C1F201E145C54F FOREIGN KEY (oekKlant_id) REFERENCES oek_klanten (id)');
        $this->addSql('ALTER TABLE oek_deelnames ADD CONSTRAINT FK_A6C1F2014DF034FD FOREIGN KEY (oekDeelnameStatus_id) REFERENCES oek_deelname_statussen (id)');
        $this->addSql('ALTER TABLE oek_trainingen ADD CONSTRAINT FK_B0D582D43B3F0A5 FOREIGN KEY (oekGroep_id) REFERENCES oek_groepen (id)');
        $this->addSql('ALTER TABLE oek_deelname_statussen ADD CONSTRAINT FK_4CBB9BCD6D7A74BD FOREIGN KEY (oekDeelname_id) REFERENCES oek_deelnames (id)');
        $this->addSql('ALTER TABLE oek_klanten ADD CONSTRAINT FK_A501F8F723473A1F FOREIGN KEY (oekDossierStatus_id) REFERENCES oek_dossier_statussen (id)');
        $this->addSql('ALTER TABLE oek_klanten ADD CONSTRAINT FK_A501F8F7C45AE93C FOREIGN KEY (oekAanmelding_id) REFERENCES oek_dossier_statussen (id)');
        $this->addSql('ALTER TABLE oek_klanten ADD CONSTRAINT FK_A501F8F7B99C329A FOREIGN KEY (oekAfsluiting_id) REFERENCES oek_dossier_statussen (id)');
        $this->addSql('ALTER TABLE oek_klanten ADD CONSTRAINT FK_A501F8F73C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE oek_klanten ADD CONSTRAINT FK_A501F8F73D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE oekklant_oekdossierstatus ADD CONSTRAINT FK_1EF9C0A61833A719 FOREIGN KEY (oekklant_id) REFERENCES oek_klanten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oekklant_oekdossierstatus ADD CONSTRAINT FK_1EF9C0A6B689C3C1 FOREIGN KEY (oekdossierstatus_id) REFERENCES oek_dossier_statussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oek_lidmaatschappen ADD CONSTRAINT FK_7B0B7DFF43B3F0A5 FOREIGN KEY (oekGroep_id) REFERENCES oek_groepen (id)');
        $this->addSql('ALTER TABLE oek_lidmaatschappen ADD CONSTRAINT FK_7B0B7DFFE145C54F FOREIGN KEY (oekKlant_id) REFERENCES oek_klanten (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
