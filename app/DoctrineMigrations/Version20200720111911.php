<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200720111911 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mw_dossier_statussen ADD locatie_id INT NULL');
        $this->addSql('UPDATE mw_dossier_statussen SET locatie_id = 1 WHERE class = \'Afsluiting\' AND locatie_id = 0');
        $this->addSql('ALTER TABLE mw_dossier_statussen ADD CONSTRAINT FK_D74783BB4947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id)');
        $this->addSql('CREATE INDEX IDX_D74783BB4947630C ON mw_dossier_statussen (locatie_id)');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE mw_dossier_statussen DROP FOREIGN KEY FK_D74783BB4947630C');
        $this->addSql('DROP INDEX IDX_D74783BB4947630C ON mw_dossier_statussen');
        $this->addSql('ALTER TABLE mw_dossier_statussen DROP locatie_id');


    }
}
