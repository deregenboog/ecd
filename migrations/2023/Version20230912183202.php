<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230912183202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs


        $this->addSql('ALTER TABLE mw_dossier_statussen CHANGE binnenViaOptieKlant_id binnenViaOptieKlant_id INT NULL DEFAULT NULL');


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE mw_dossier_statussen CHANGE binnenViaOptieKlant_id binnenViaOptieKlant_id INT DEFAULT 0 NOT NULL');


    }
}
