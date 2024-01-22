<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211216114235 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tw_deelnemers ADD huisgenoot_id INT DEFAULT NULL, ADD samenvatting LONGTEXT DEFAULT NULL,ADD huurprijs INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tw_huuraanbiedingen ADD huurprijs INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E431725635972C83 FOREIGN KEY (huisgenoot_id) REFERENCES tw_deelnemers (id)');
        $this->addSql('CREATE INDEX IDX_E431725635972C83 ON tw_deelnemers (huisgenoot_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E431725635972C83');
        $this->addSql('DROP INDEX IDX_E431725635972C83 ON tw_deelnemers');
        $this->addSql('ALTER TABLE tw_deelnemers  DROP huisgenoot_id');
    }
}
