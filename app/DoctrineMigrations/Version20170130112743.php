<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170130112743 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_deelnemers ADD medewerker_id INT NOT NULL');
        $this->addSql('ALTER TABLE odp_deelnemers ADD CONSTRAINT FK_202839993D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_202839993D707F64 ON odp_deelnemers (medewerker_id)');
        $this->addSql('ALTER TABLE odp_huuraanbiedingen ADD medewerker_id INT NOT NULL');
        $this->addSql('ALTER TABLE odp_huuraanbiedingen ADD CONSTRAINT FK_FA204F873D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_FA204F873D707F64 ON odp_huuraanbiedingen (medewerker_id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP FOREIGN KEY FK_96E70940817293');
        $this->addSql('DROP INDEX IDX_96E70940817293 ON odp_huurovereenkomsten');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP FOREIGN KEY FK_96E7093D707F64');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP FOREIGN KEY FK_96E709A0591DDA');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP FOREIGN KEY FK_96E709CEA1B462');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten CHANGE odphuurperiodeafsluiting_id odpHuurovereenkomstAfsluiting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_453FF4A6A93FA8D FOREIGN KEY (odpHuurovereenkomstAfsluiting_id) REFERENCES odp_huurovereenkomst_afsluitingen (id)');
        $this->addSql('CREATE INDEX IDX_453FF4A6A93FA8D ON odp_huurovereenkomsten (odpHuurovereenkomstAfsluiting_id)');
        $this->addSql('DROP INDEX idx_96e7093d707f64 ON odp_huurovereenkomsten');
        $this->addSql('CREATE INDEX IDX_453FF4A63D707F64 ON odp_huurovereenkomsten (medewerker_id)');
        $this->addSql('DROP INDEX idx_96e709a0591dda ON odp_huurovereenkomsten');
        $this->addSql('CREATE INDEX IDX_453FF4A6A0591DDA ON odp_huurovereenkomsten (odpHuuraanbod_id)');
        $this->addSql('DROP INDEX idx_96e709cea1b462 ON odp_huurovereenkomsten');
        $this->addSql('CREATE INDEX IDX_453FF4A6CEA1B462 ON odp_huurovereenkomsten (odpHuurverzoek_id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_96E7093D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_96E709A0591DDA FOREIGN KEY (odpHuuraanbod_id) REFERENCES odp_huuraanbiedingen (id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_96E709CEA1B462 FOREIGN KEY (odpHuurverzoek_id) REFERENCES odp_huurverzoeken (id)');
        $this->addSql('ALTER TABLE odp_huurverzoeken ADD medewerker_id INT NOT NULL');
        $this->addSql('ALTER TABLE odp_huurverzoeken ADD CONSTRAINT FK_588F4E963D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_588F4E963D707F64 ON odp_huurverzoeken (medewerker_id)');
        $this->addSql('ALTER TABLE odp_verslagen DROP FOREIGN KEY FK_762D3F7748361CF5');
        $this->addSql('DROP INDEX UNIQ_762D3F7748361CF5 ON odp_verslagen');
        $this->addSql('ALTER TABLE odp_verslagen CHANGE odphuurperiode_id odpHuurovereenkomst_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_verslagen ADD CONSTRAINT FK_762D3F77F3F715C9 FOREIGN KEY (odpHuurovereenkomst_id) REFERENCES odp_huurovereenkomsten (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_762D3F77F3F715C9 ON odp_verslagen (odpHuurovereenkomst_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_deelnemers DROP FOREIGN KEY FK_202839993D707F64');
        $this->addSql('DROP INDEX IDX_202839993D707F64 ON odp_deelnemers');
        $this->addSql('ALTER TABLE odp_deelnemers DROP medewerker_id');
        $this->addSql('ALTER TABLE odp_huuraanbiedingen DROP FOREIGN KEY FK_FA204F873D707F64');
        $this->addSql('DROP INDEX IDX_FA204F873D707F64 ON odp_huuraanbiedingen');
        $this->addSql('ALTER TABLE odp_huuraanbiedingen DROP medewerker_id');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP FOREIGN KEY FK_453FF4A6A93FA8D');
        $this->addSql('DROP INDEX IDX_453FF4A6A93FA8D ON odp_huurovereenkomsten');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP FOREIGN KEY FK_453FF4A63D707F64');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP FOREIGN KEY FK_453FF4A6A0591DDA');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP FOREIGN KEY FK_453FF4A6CEA1B462');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten CHANGE odphuurovereenkomstafsluiting_id odpHuurperiodeAfsluiting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_96E70940817293 FOREIGN KEY (odpHuurperiodeAfsluiting_id) REFERENCES odp_huurovereenkomst_afsluitingen (id)');
        $this->addSql('CREATE INDEX IDX_96E70940817293 ON odp_huurovereenkomsten (odpHuurperiodeAfsluiting_id)');
        $this->addSql('DROP INDEX idx_453ff4a63d707f64 ON odp_huurovereenkomsten');
        $this->addSql('CREATE INDEX IDX_96E7093D707F64 ON odp_huurovereenkomsten (medewerker_id)');
        $this->addSql('DROP INDEX idx_453ff4a6a0591dda ON odp_huurovereenkomsten');
        $this->addSql('CREATE INDEX IDX_96E709A0591DDA ON odp_huurovereenkomsten (odpHuuraanbod_id)');
        $this->addSql('DROP INDEX idx_453ff4a6cea1b462 ON odp_huurovereenkomsten');
        $this->addSql('CREATE INDEX IDX_96E709CEA1B462 ON odp_huurovereenkomsten (odpHuurverzoek_id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_453FF4A63D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_453FF4A6A0591DDA FOREIGN KEY (odpHuuraanbod_id) REFERENCES odp_huuraanbiedingen (id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_453FF4A6CEA1B462 FOREIGN KEY (odpHuurverzoek_id) REFERENCES odp_huurverzoeken (id)');
        $this->addSql('ALTER TABLE odp_huurverzoeken DROP FOREIGN KEY FK_588F4E963D707F64');
        $this->addSql('DROP INDEX IDX_588F4E963D707F64 ON odp_huurverzoeken');
        $this->addSql('ALTER TABLE odp_huurverzoeken DROP medewerker_id');
        $this->addSql('ALTER TABLE odp_verslagen DROP FOREIGN KEY FK_762D3F77F3F715C9');
        $this->addSql('DROP INDEX UNIQ_762D3F77F3F715C9 ON odp_verslagen');
        $this->addSql('ALTER TABLE odp_verslagen CHANGE odphuurovereenkomst_id odpHuurperiode_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_verslagen ADD CONSTRAINT FK_762D3F7748361CF5 FOREIGN KEY (odpHuurperiode_id) REFERENCES odp_huurovereenkomsten (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_762D3F7748361CF5 ON odp_verslagen (odpHuurperiode_id)');
    }
}
