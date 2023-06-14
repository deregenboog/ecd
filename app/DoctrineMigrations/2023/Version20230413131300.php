<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230413131300 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Relation tussen klant en verslaginfos (mw dossier)';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE klanten ADD info_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE klanten ADD CONSTRAINT FK_F538C5BC5D8BC1F8 FOREIGN KEY (info_id) REFERENCES verslaginfos (id)');
        $this->addSql('CREATE INDEX IDX_F538C5BC5D8BC1F8 ON klanten (info_id)');
        $this->addSql('INSERT IGNORE INTO verslaginfos (klant_id) SELECT k.id FROM klanten AS k INNER JOIN mw_dossier_statussen AS mds ON mds.id = k.huidigeMwStatus_id AND mds.class = \'Aanmelding\'');
        $this->addSql('UPDATE klanten AS k LEFT JOIN verslaginfos AS v ON v.klant_id = k.id SET k.info_id = v.id');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE klanten DROP info_id');


        $this->addSql('ALTER TABLE verslaginfos CHANGE klant_id klant_id INT NOT NULL, CHANGE isGezin isGezin TINYINT(1) DEFAULT \'0\'');

    }
}
