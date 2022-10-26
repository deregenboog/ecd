<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170330122223 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP FOREIGN KEY FK_453FF4A660850352');
        $this->addSql('CREATE TABLE odp_afsluitingen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE odp_deelnemers DROP FOREIGN KEY FK_202839997E366551');
        $this->addSql('DROP INDEX IDX_202839997E366551 ON odp_deelnemers');
        $this->addSql('ALTER TABLE odp_deelnemers CHANGE foreign_key klant_id INT NOT NULL');
        $this->addSql('ALTER TABLE odp_deelnemers ADD CONSTRAINT FK_20283999ECDAD1A9 FOREIGN KEY (afsluiting_id) REFERENCES odp_afsluitingen (id)');
        $this->addSql('ALTER TABLE odp_deelnemers ADD CONSTRAINT FK_202839993C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('CREATE INDEX IDX_202839993C427B2F ON odp_deelnemers (klant_id)');
        $this->addSql('ALTER TABLE odp_huurverzoeken ADD afsluiting_id INT DEFAULT NULL, DROP actief, CHANGE einddatum afsluitdatum DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_huurverzoeken ADD CONSTRAINT FK_588F4E96ECDAD1A9 FOREIGN KEY (afsluiting_id) REFERENCES odp_afsluitingen (id)');
        $this->addSql('CREATE INDEX IDX_588F4E96ECDAD1A9 ON odp_huurverzoeken (afsluiting_id)');
        $this->addSql('ALTER TABLE odp_huuraanbiedingen ADD afsluiting_id INT DEFAULT NULL, DROP actief, CHANGE einddatum afsluitdatum DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_huuraanbiedingen ADD CONSTRAINT FK_FA204F87ECDAD1A9 FOREIGN KEY (afsluiting_id) REFERENCES odp_afsluitingen (id)');
        $this->addSql('CREATE INDEX IDX_FA204F87ECDAD1A9 ON odp_huuraanbiedingen (afsluiting_id)');
        $this->addSql('DROP INDEX IDX_453FF4A660850352 ON odp_huurovereenkomsten');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten CHANGE huurovereenkomstafsluiting_id afsluiting_id INT DEFAULT NULL, CHANGE einddatum afsluitdatum DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_453FF4A6ECDAD1A9 FOREIGN KEY (afsluiting_id) REFERENCES odp_afsluitingen (id)');
        $this->addSql('CREATE INDEX IDX_453FF4A6ECDAD1A9 ON odp_huurovereenkomsten (afsluiting_id)');
    }

    /**
     * @param Schema $schema
     */
     public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
