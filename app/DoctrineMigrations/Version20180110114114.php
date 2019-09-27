<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180110114114 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE iz_deelnemers ADD deleted DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE iz_koppelingen ADD deleted DATETIME DEFAULT NULL');

        $this->addSql('UPDATE `iz_deelnemers` SET `deleted` = `modified` WHERE `iz_afsluiting_id` = 10');
        $this->addSql('UPDATE `iz_koppelingen` SET `deleted` = `modified` WHERE `iz_eindekoppeling_id` = 10');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
