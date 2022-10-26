<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221021100243 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE medewerkers CHANGE `active` `actief` INT NOT NULL DEFAULT '1'");
        $this->addSql("ALTER TABLE `medewerkers` CHANGE `groups` `groups_depr` LONGTEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '(DC2Type:json)'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE medewerkers CHANGE `actief` `active` INT NOT NULL DEFAULT '1'");
        $this->addSql("ALTER TABLE `medewerkers` CHANGE `groups_depr` `groups` LONGTEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '(DC2Type:json)'");

    }
}
