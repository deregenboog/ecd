<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230831070642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Alter tablename verslaginfos to mw_dossier_info';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("RENAME TABLE `verslaginfos` TO `mw_dossier_info`;");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("RENAME TABLE `mw_dossier_info` TO `verslaginfos`;");

    }
}
