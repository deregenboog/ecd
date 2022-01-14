<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220114125437 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dagbesteding_dagdelen ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('ALTER TABLE oek_deelname_statussen ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('ALTER TABLE scip_deelnemers ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW()');
        $this->addSql('ALTER TABLE uhk_deelnemers ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW()');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE dagbesteding_dagdelen DROP  created, DROP modified');

        $this->addSql('ALTER TABLE oek_deelname_statussen DROP created, DROP modified');

        $this->addSql('ALTER TABLE scip_deelnemers DROP created, DROP modified');
        $this->addSql('ALTER TABLE uhk_deelnemers DROP created, DROP modified');
    }
}
