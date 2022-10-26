<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190205151956 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE klanten DROP INDEX UNIQ_F538C5BC8B2671BD, ADD INDEX IDX_F538C5BC8B2671BD (huidigeStatus_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F538C5BC8B2671BDEB3B4E33 ON klanten (huidigeStatus_id, deleted)');
    }

     public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
