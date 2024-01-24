<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230719080951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Alter FK constraints for oek deelnamestatussen';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `oek_deelnames` DROP FOREIGN KEY `FK_A6C1F2014DF034FD`');
        $this->addSql('ALTER TABLE `oek_deelnames` ADD CONSTRAINT `FK_A6C1F2014DF034FD` FOREIGN KEY (`oekDeelnameStatus_id`) REFERENCES `oek_deelname_statussen`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;');
        $this->addSql('ALTER TABLE `oek_deelname_statussen` DROP FOREIGN KEY `FK_4CBB9BCD6D7A74BD`');
        $this->addSql('ALTER TABLE oek_deelname_statussen ADD CONSTRAINT FK_4CBB9BCD6D7A74BD FOREIGN KEY (oekDeelname_id) REFERENCES oek_deelnames (id) ON DELETE CASCADE ON UPDATE RESTRICT');


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `oek_deelnames` DROP FOREIGN KEY `FK_A6C1F2014DF034FD`');
        $this->addSql('ALTER TABLE `oek_deelnames` ADD CONSTRAINT `FK_A6C1F2014DF034FD` FOREIGN KEY (`oekDeelnameStatus_id`) REFERENCES `oek_deelname_statussen`(`id`)');
        $this->addSql('ALTER TABLE `oek_deelname_statussen` DROP FOREIGN KEY `FK_4CBB9BCD6D7A74BD`');
        $this->addSql('ALTER TABLE oek_deelname_statussen ADD CONSTRAINT FK_4CBB9BCD6D7A74BD FOREIGN KEY (oekDeelname_id) REFERENCES oek_deelnames (id)');

    }
}
