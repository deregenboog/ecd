<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180417080553 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP INDEX IDX_453FF4A6656E2280, ADD UNIQUE INDEX UNIQ_453FF4A6656E2280 (huuraanbod_id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP INDEX IDX_453FF4A645DA3BB7, ADD UNIQUE INDEX UNIQ_453FF4A645DA3BB7 (huurverzoek_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
