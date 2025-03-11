<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250307084457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '#1811-Adding new vrijwilliger tables for oekraine';
    }

    public function up(Schema $schema): void
    {
        /// oekraine_training
        $this->addSql(<<<SQL
            CREATE TABLE `oekraine_training` (
                `id` int NOT NULL AUTO_INCREMENT,
                `naam` varchar(255) NOT NULL,
                `active` tinyint(1) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3
        SQL);
        
        /// oekraine_memos
        $this->addSql(<<<SQL
            CREATE TABLE `oekraine_memos` (
                `id` int NOT NULL AUTO_INCREMENT,
                `medewerker_id` int NOT NULL,
                `datum` datetime NOT NULL,
                `onderwerp` varchar(255) NOT NULL,
                `memo` longtext NOT NULL,
                `intake` tinyint(1) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `IDX_9ACAE40D3D707F65` (`medewerker_id`),
                CONSTRAINT `FK_9ACAE40D3D707F65` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3
        SQL);

        /// oekraine_binnen_via
        $this->addSql(<<<SQL
            CREATE TABLE `oekraine_binnen_via` (
                `id` int NOT NULL AUTO_INCREMENT,
                `naam` varchar(255) NOT NULL,
                `active` tinyint(1) NOT NULL,
                `created` datetime NOT NULL,
                `modified` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3
        SQL);

        /// oekraine_vrijwilligers
        $this->addSql(<<<SQL
            CREATE TABLE `oekraine_vrijwilligers` (
                `id` int NOT NULL AUTO_INCREMENT,
                `vrijwilliger_id` int NOT NULL,
                `medewerker_id` int NOT NULL,
                `created` datetime NOT NULL,
                `modified` datetime NOT NULL,
                `aanmelddatum` date NOT NULL,
                `binnen_via_id` int DEFAULT NULL,
                `afsluitreden_id` int DEFAULT NULL,
                `afsluitdatum` date DEFAULT NULL,
                `stagiair` tinyint(1) NOT NULL,
                `startdatum` date DEFAULT NULL,
                `notitieIntake` varchar(255) DEFAULT NULL,
                `datumNotitieIntake` date DEFAULT NULL,
                `medewerkerLocatie_id` int DEFAULT NULL,
                `trainingOverig` varchar(255) DEFAULT NULL,
                `trainingOverigDatum` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `UNIQ_5611048076B43BDC` (`vrijwilliger_id`),
                KEY `IDX_561104803D707F65` (`medewerker_id`),
                KEY `IDX_561104804C676E6B` (`binnen_via_id`),
                KEY `IDX_56110480CA12F7AF` (`afsluitreden_id`),
                KEY `IDX_56110480EA9C84FF` (`medewerkerLocatie_id`),
                CONSTRAINT `FK_561104803D707F65` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
                CONSTRAINT `FK_5611048076B43BDD` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
                CONSTRAINT `FK_56110480CA12F7AF` FOREIGN KEY (`afsluitreden_id`) REFERENCES `oekraine_afsluitredenen_vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
                CONSTRAINT `FK_56110480D8471946` FOREIGN KEY (`binnen_via_id`) REFERENCES `oekraine_binnen_via` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
                CONSTRAINT `FK_56110480EA9C84FF` FOREIGN KEY (`medewerkerLocatie_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3
        SQL);        
        
        /// oekraine_vrijwilliger_document
        $this->addSql(<<<SQL
            CREATE TABLE `oekraine_vrijwilliger_document` (
                `vrijwilliger_id` int NOT NULL,
                `document_id` int NOT NULL,
                PRIMARY KEY (`vrijwilliger_id`,`document_id`),
                UNIQUE KEY `UNIQ_6401B15DC33F7838` (`document_id`),
                KEY `IDX_6401B15D76B43BDD` (`vrijwilliger_id`),
                CONSTRAINT `FK_6401B15D76B43BDD` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `oekraine_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
                CONSTRAINT `FK_6401B15DC33F7838` FOREIGN KEY (`document_id`) REFERENCES `oekraine_documenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3
        SQL);

        /// oekraine_vrijwilliger_locatie
        $this->addSql(<<<SQL
            CREATE TABLE `oekraine_vrijwilliger_locatie` (
                `vrijwilliger_id` int NOT NULL,
                `locatie_id` int NOT NULL,
                PRIMARY KEY (`vrijwilliger_id`,`locatie_id`),
                KEY `IDX_A1776D9F76B43BDD` (`vrijwilliger_id`),
                KEY `IDX_A1776D9F4947630E` (`locatie_id`),
                CONSTRAINT `FK_A1776D9F4947630E` FOREIGN KEY (`locatie_id`) REFERENCES `oekraine_locaties` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
                CONSTRAINT `FK_A1776D9F76B43BDD` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `oekraine_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3
        SQL);

        /// oekraine_deelnames
        $this->addSql(<<<SQL
            CREATE TABLE `oekraine_deelnames` (
                `id` int NOT NULL AUTO_INCREMENT,
                `oekraine_vrijwilliger_id` int NOT NULL,
                `datum` date DEFAULT NULL,
                `created` datetime NOT NULL,
                `modified` datetime NOT NULL,
                `oekraineTraining_id` int NOT NULL,
                `overig` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `IDX_CFB194F341D2A6E0` (`oekraineTraining_id`),
                KEY `IDX_CFB194F3B280D298` (`oekraine_vrijwilliger_id`),
                CONSTRAINT `FK_CFB194F341D2A6E0` FOREIGN KEY (`oekraineTraining_id`) REFERENCES `oekraine_training` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
                CONSTRAINT `FK_CFB194F3B280D298` FOREIGN KEY (`oekraine_vrijwilliger_id`) REFERENCES `oekraine_vrijwilligers` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3
        SQL);

        /// oekraine_vrijwilliger_memo
        $this->addSql(<<<SQL
            CREATE TABLE `oekraine_vrijwilliger_memo` (
                `vrijwilliger_id` int NOT NULL,
                `memo_id` int NOT NULL,
                PRIMARY KEY (`vrijwilliger_id`,`memo_id`),
                UNIQUE KEY `UNIQ_94FA9B19B4D3243F` (`memo_id`),
                KEY `IDX_94FA9B1976B43BDD` (`vrijwilliger_id`),
                CONSTRAINT `FK_94FA9B1976B43BDD` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `oekraine_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
                CONSTRAINT `FK_94FA9B19B4D3243F` FOREIGN KEY (`memo_id`) REFERENCES `oekraine_memos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
