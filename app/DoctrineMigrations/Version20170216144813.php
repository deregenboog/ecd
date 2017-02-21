<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170216144813 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE oek_dossier_statussen (id INT AUTO_INCREMENT NOT NULL, verwijzing_id INT NOT NULL, medewerker_id INT NOT NULL, datum DATE NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, oekKlant_id INT NOT NULL, class VARCHAR(255) NOT NULL, INDEX IDX_D8FAC765E145C54F (oekKlant_id), INDEX IDX_D8FAC765D8B4CBDF (verwijzing_id), INDEX IDX_D8FAC7653D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oek_verwijzingen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, actief TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, class VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekklant_oekdossierstatus (oekklant_id INT NOT NULL, oekdossierstatus_id INT NOT NULL, INDEX IDX_1EF9C0A61833A719 (oekklant_id), INDEX IDX_1EF9C0A6B689C3C1 (oekdossierstatus_id), PRIMARY KEY(oekklant_id, oekdossierstatus_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE oek_dossier_statussen ADD CONSTRAINT FK_D8FAC765E145C54F FOREIGN KEY (oekKlant_id) REFERENCES oek_klanten (id)');
        $this->addSql('ALTER TABLE oek_dossier_statussen ADD CONSTRAINT FK_D8FAC765D8B4CBDF FOREIGN KEY (verwijzing_id) REFERENCES oek_verwijzingen (id)');
        $this->addSql('ALTER TABLE oek_dossier_statussen ADD CONSTRAINT FK_D8FAC7653D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE oekklant_oekdossierstatus ADD CONSTRAINT FK_1EF9C0A61833A719 FOREIGN KEY (oekklant_id) REFERENCES oek_klanten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oekklant_oekdossierstatus ADD CONSTRAINT FK_1EF9C0A6B689C3C1 FOREIGN KEY (oekdossierstatus_id) REFERENCES oek_dossier_statussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oek_klanten ADD medewerker_id INT NOT NULL, ADD opmerking LONGTEXT DEFAULT NULL, ADD oekDossierStatus_id INT DEFAULT NULL, ADD oekAanmelding_id INT DEFAULT NULL, ADD oekAfsluiting_id INT DEFAULT NULL, DROP aanmelding, DROP verwijzing_door, DROP afsluiting, DROP verwijzing_naar');
        $this->addSql('ALTER TABLE oek_klanten ADD CONSTRAINT FK_A501F8F723473A1F FOREIGN KEY (oekDossierStatus_id) REFERENCES oek_dossier_statussen (id)');
        $this->addSql('ALTER TABLE oek_klanten ADD CONSTRAINT FK_A501F8F7C45AE93C FOREIGN KEY (oekAanmelding_id) REFERENCES oek_dossier_statussen (id)');
        $this->addSql('ALTER TABLE oek_klanten ADD CONSTRAINT FK_A501F8F7B99C329A FOREIGN KEY (oekAfsluiting_id) REFERENCES oek_dossier_statussen (id)');
        $this->addSql('ALTER TABLE oek_klanten ADD CONSTRAINT FK_A501F8F73D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_A501F8F723473A1F ON oek_klanten (oekDossierStatus_id)');
        $this->addSql('CREATE INDEX IDX_A501F8F7C45AE93C ON oek_klanten (oekAanmelding_id)');
        $this->addSql('CREATE INDEX IDX_A501F8F7B99C329A ON oek_klanten (oekAfsluiting_id)');
        $this->addSql('CREATE INDEX IDX_A501F8F73D707F64 ON oek_klanten (medewerker_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
