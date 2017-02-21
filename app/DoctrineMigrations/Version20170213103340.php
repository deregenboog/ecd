<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170213103340 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_huuraanbiedingen CHANGE startdatum startdatum DATE NOT NULL, CHANGE einddatum einddatum DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten CHANGE startdatum startdatum DATE NOT NULL, CHANGE einddatum einddatum DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_huurverzoeken CHANGE startdatum startdatum DATE NOT NULL, CHANGE einddatum einddatum DATE DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_huuraanbiedingen CHANGE startdatum startdatum DATETIME NOT NULL, CHANGE einddatum einddatum DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten CHANGE startdatum startdatum DATETIME NOT NULL, CHANGE einddatum einddatum DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_huurverzoeken CHANGE startdatum startdatum DATETIME NOT NULL, CHANGE einddatum einddatum DATETIME DEFAULT NULL');
    }
}
