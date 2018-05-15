<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180515103809 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE iz_reserveringen (id INT AUTO_INCREMENT NOT NULL, hulpvraag_id INT NOT NULL, hulpaanbod_id INT NOT NULL, medewerker_id INT NOT NULL, deleted DATETIME DEFAULT NULL, startdatum DATE NOT NULL, einddatum DATE NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_B9D71E14A8450D8C (hulpvraag_id), INDEX IDX_B9D71E14B42008F3 (hulpaanbod_id), INDEX IDX_B9D71E143D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iz_reserveringen ADD CONSTRAINT FK_B9D71E14A8450D8C FOREIGN KEY (hulpvraag_id) REFERENCES iz_koppelingen (id)');
        $this->addSql('ALTER TABLE iz_reserveringen ADD CONSTRAINT FK_B9D71E14B42008F3 FOREIGN KEY (hulpaanbod_id) REFERENCES iz_koppelingen (id)');
        $this->addSql('ALTER TABLE iz_reserveringen ADD CONSTRAINT FK_B9D71E143D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
