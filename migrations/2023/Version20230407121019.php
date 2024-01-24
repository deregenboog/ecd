<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230407121019 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'adds extra info to dossier status for MW';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE mw_dossier_statussen ADD zachteLanding TINYINT(1) DEFAULT NULL, ADD kosten VARCHAR(255) DEFAULT NULL, ADD datumRepatriering DATE DEFAULT NULL');



    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mw_dossier_statussen DROP zachteLanding, DROP kosten, DROP datumRepatriering');


    }
}
