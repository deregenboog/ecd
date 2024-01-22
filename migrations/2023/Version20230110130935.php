<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230110130935 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create table mw_projecten';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(!$this->connection->getDatabasePlatform() instanceof MySQLPlatform, 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mw_projecten (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, `active` TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mw_dossier_statussen ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mw_dossier_statussen ADD CONSTRAINT FK_D74783BB166D1F9C FOREIGN KEY (project_id) REFERENCES mw_projecten (id)');
        $this->addSql('CREATE INDEX IDX_D74783BB166D1F9C ON mw_dossier_statussen (project_id)');
        $this->addSql('CREATE TABLE mw_project_medewerker (project_id INT NOT NULL, medewerker_id INT NOT NULL, INDEX IDX_FE9AC3F7166D1F9C (project_id), INDEX IDX_FE9AC3F73D707F64 (medewerker_id), PRIMARY KEY(project_id, medewerker_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mw_project_medewerker ADD CONSTRAINT FK_FE9AC3F7166D1F9C FOREIGN KEY (project_id) REFERENCES mw_projecten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_project_medewerker ADD CONSTRAINT FK_FE9AC3F73D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(!$this->connection->getDatabasePlatform() instanceof MySQLPlatform, 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mw_project_medewerker');
        $this->addSql('ALTER TABLE mw_dossier_statussen DROP FOREIGN KEY FK_D74783BB166D1F9C');
        $this->addSql('DROP INDEX IDX_D74783BB166D1F9C ON mw_dossier_statussen');
        $this->addSql('ALTER TABLE mw_dossier_statussen DROP project_id');
        $this->addSql('DROP TABLE mw_projecten');
    }
}
