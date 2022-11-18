<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221116145922 extends AbstractMigration
{
    public function getDescription() : string
    {
        return "Change SQL Comment on columns with DC2 type JSON which is not compatible with SF4.";
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE `medewerkers` CHANGE `groups` `groups` LONGTEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL");
        $this->addSql("ALTER TABLE `medewerkers` CHANGE `ldap_groups` `ldap_groups` LONGTEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL");
        $this->addSql("ALTER TABLE `medewerkers` CHANGE `roles` `roles` LONGTEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL");

        // this up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `medewerkers` CHANGE `groups` `groups` LONGTEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '(DC2Type:json_array)'");
        $this->addSql("ALTER TABLE `medewerkers` CHANGE `ldap_groups` `ldap_groups` LONGTEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '(DC2Type:json_array)'");
        $this->addSql("ALTER TABLE `medewerkers` CHANGE `roles` `roles` LONGTEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '(DC2Type:json_array)'");

    }
}
