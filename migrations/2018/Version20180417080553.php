<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180417080553 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP INDEX IDX_453FF4A6656E2280, ADD UNIQUE INDEX UNIQ_453FF4A6656E2280 (huuraanbod_id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten DROP INDEX IDX_453FF4A645DA3BB7, ADD UNIQUE INDEX UNIQ_453FF4A645DA3BB7 (huurverzoek_id)');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
