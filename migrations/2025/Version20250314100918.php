<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250314100918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add uhk_projecten table for Uit het krijt';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            CREATE TABLE `uhk_projecten` (
                `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `naam` varchar(255) NOT NULL,
                `color` varchar(10) NOT NULL DEFAULT '#FF0000',
                `active` tinyint(1) NOT NULL DEFAULT '1',
                `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        SQL);

        $this->addSql(<<<SQL
            CREATE TABLE `uhk_deelnemers_uhk_projecten`  (
                `uhk_deelnemer_id` int NOT NULL,
                `uhk_project_id` int NOT NULL,
                PRIMARY KEY (`uhk_deelnemer_id`, `uhk_project_id`) USING BTREE,
                INDEX `IDX_34E5855EB36F4CA6`(`uhk_deelnemer_id` ASC) USING BTREE,
                INDEX `IDX_34E5855E3B4A66E4`(`uhk_project_id` ASC) USING BTREE,
                CONSTRAINT `FK_48E405357776076B` FOREIGN KEY (`uhk_deelnemer_id`) REFERENCES `uhk_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
                CONSTRAINT `FK_48E40535FF532D2E` FOREIGN KEY (`uhk_project_id`) REFERENCES `uhk_projecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE uhk_projecten');
        $this->addSql('ALTER TABLE uhk_deelnemers_uhk_projecten');
    }
}
