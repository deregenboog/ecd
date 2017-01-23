<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170123184955 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE odp_deelnemer_afsluitingen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_deelnemers (id INT AUTO_INCREMENT NOT NULL, foreign_key INT NOT NULL, aanmelddatum DATE NOT NULL, afsluitdatum DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, odpDeelnemerAfsluiting_id INT DEFAULT NULL, model VARCHAR(255) NOT NULL, INDEX IDX_202839992326B688 (odpDeelnemerAfsluiting_id), INDEX IDX_202839997E366551 (foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_huuraanbiedingen (id INT AUTO_INCREMENT NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, odpVerhuurder_id INT DEFAULT NULL, INDEX IDX_FA204F87BB2F7707 (odpVerhuurder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_intakes (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, intake_datum DATE NOT NULL, gezin_met_kinderen TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, odpDeelnemer_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_3A1E7F77D0FEB319 (odpDeelnemer_id), INDEX IDX_3A1E7F773D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_huurovereenkomsten (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, startdatum DATETIME NOT NULL, einddatum DATETIME NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, odpHuuraanbod_id INT DEFAULT NULL, odpHuurverzoek_id INT DEFAULT NULL, odpHuurperiodeAfsluiting_id INT DEFAULT NULL, INDEX IDX_96E7093D707F64 (medewerker_id), INDEX IDX_96E709A0591DDA (odpHuuraanbod_id), INDEX IDX_96E709CEA1B462 (odpHuurverzoek_id), INDEX IDX_96E70940817293 (odpHuurperiodeAfsluiting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_verslagen (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, opmerking LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, odpDeelnemer_id INT DEFAULT NULL, odpHuurperiode_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_762D3F77D0FEB319 (odpDeelnemer_id), UNIQUE INDEX UNIQ_762D3F7748361CF5 (odpHuurperiode_id), INDEX IDX_762D3F773D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_huurverzoeken (id INT AUTO_INCREMENT NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, odpHuurder_id INT DEFAULT NULL, INDEX IDX_588F4E96C11E1211 (odpHuurder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_huurovereenkomst_afsluitingen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE odp_deelnemers ADD CONSTRAINT FK_202839992326B688 FOREIGN KEY (odpDeelnemerAfsluiting_id) REFERENCES odp_deelnemer_afsluitingen (id)');
        $this->addSql('ALTER TABLE odp_deelnemers ADD CONSTRAINT FK_202839997E366551 FOREIGN KEY (foreign_key) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE odp_huuraanbiedingen ADD CONSTRAINT FK_FA204F87BB2F7707 FOREIGN KEY (odpVerhuurder_id) REFERENCES odp_deelnemers (id)');
        $this->addSql('ALTER TABLE odp_intakes ADD CONSTRAINT FK_3A1E7F77D0FEB319 FOREIGN KEY (odpDeelnemer_id) REFERENCES odp_deelnemers (id)');
        $this->addSql('ALTER TABLE odp_intakes ADD CONSTRAINT FK_3A1E7F773D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_96E7093D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_96E709A0591DDA FOREIGN KEY (odpHuuraanbod_id) REFERENCES odp_huuraanbiedingen (id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_96E709CEA1B462 FOREIGN KEY (odpHuurverzoek_id) REFERENCES odp_huurverzoeken (id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_96E70940817293 FOREIGN KEY (odpHuurperiodeAfsluiting_id) REFERENCES odp_huurovereenkomst_afsluitingen (id)');
        $this->addSql('ALTER TABLE odp_verslagen ADD CONSTRAINT FK_762D3F77D0FEB319 FOREIGN KEY (odpDeelnemer_id) REFERENCES odp_deelnemers (id)');
        $this->addSql('ALTER TABLE odp_verslagen ADD CONSTRAINT FK_762D3F7748361CF5 FOREIGN KEY (odpHuurperiode_id) REFERENCES odp_huurovereenkomsten (id)');
        $this->addSql('ALTER TABLE odp_verslagen ADD CONSTRAINT FK_762D3F773D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE odp_huurverzoeken ADD CONSTRAINT FK_588F4E96C11E1211 FOREIGN KEY (odpHuurder_id) REFERENCES odp_deelnemers (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_deelnemers DROP FOREIGN KEY FK_202839992326B688');
        $this->addSql('ALTER TABLE odp_huuraanbiedingen DROP FOREIGN KEY FK_FA204F87BB2F7707');
        $this->addSql('ALTER TABLE odp_intakes DROP FOREIGN KEY FK_3A1E7F77D0FEB319');
        $this->addSql('ALTER TABLE odp_verslagen DROP FOREIGN KEY FK_762D3F77D0FEB319');
        $this->addSql('ALTER TABLE odp_huurverzoeken DROP FOREIGN KEY FK_588F4E96C11E1211');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP FOREIGN KEY FK_96E709A0591DDA');
        $this->addSql('ALTER TABLE odp_verslagen DROP FOREIGN KEY FK_762D3F7748361CF5');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP FOREIGN KEY FK_96E709CEA1B462');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP FOREIGN KEY FK_96E70940817293');
        $this->addSql('DROP TABLE odp_deelnemer_afsluitingen');
        $this->addSql('DROP TABLE odp_deelnemers');
        $this->addSql('DROP TABLE odp_huuraanbiedingen');
        $this->addSql('DROP TABLE odp_intakes');
        $this->addSql('DROP TABLE odp_huurovereenkomsten');
        $this->addSql('DROP TABLE odp_verslagen');
        $this->addSql('DROP TABLE odp_huurverzoeken');
        $this->addSql('DROP TABLE odp_huurovereenkomst_afsluitingen');
    }
}
