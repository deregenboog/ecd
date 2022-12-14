<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220311084120 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dagbesteding_deelnemers ADD werkbegeleider VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dagbesteding_trajecten ADD evaluatiedatum DATE DEFAULT NULL');
        $this->addSql('UPDATE dagbesteding_trajecten SET evaluatiedatum = DATE_ADD(startdatum, INTERVAL 6 MONTH) WHERE evaluatiedatum IS NULL AND afsluitdatum IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dagbesteding_deelnemers DROP werkbegeleider, DROP evaluatiedatum');
    }
}
