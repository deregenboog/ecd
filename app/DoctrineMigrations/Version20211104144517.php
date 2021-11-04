<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211104144517 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE tw_duurthuisloos SET active = 0");
        $this->addSql("
        ALTER TABLE `tw_duurthuisloos` ADD `label` VARCHAR(255) NOT NULL AFTER `id`;
UPDATE `tw_duurthuisloos` SET `label` = '< 3mnd' WHERE `tw_duurthuisloos`.`id` = 1;
UPDATE `tw_duurthuisloos` SET `label` = '>3 <12mnd' WHERE `tw_duurthuisloos`.`id` = 2;
UPDATE `tw_duurthuisloos` SET `label` = '>12 <24mnd' WHERE `tw_duurthuisloos`.`id` = 3;
UPDATE `tw_duurthuisloos` SET `label` = '>24 <48mnd' WHERE `tw_duurthuisloos`.`id` = 4;
UPDATE `tw_duurthuisloos` SET `label` = '>48 mnd' WHERE `tw_duurthuisloos`.`id` = 5;");
        $this->addSql("INSERT INTO tw_duurthuisloos (`label`) VALUES ('0-3 maanden'),
('3-12 maanden'),
('1 jaar'),
('2 jaar'),
('3 jaar'),
('4 jaar'),
('5 jaar'),
('6 jaar'),
('7 jaar'),
('8 jaar'),
('9 jaar'),
('10 jaar'),
('>10 jaar'),
('Onbekend')");

        $this->addSql("UPDATE tw_werk SET active = 0");
        $this->addSql("INSERT INTO tw_werk (`label`) VALUES ('1 dag'),
        ('2 dagen'),('3 dagen'),('4 dagen'),('5 dagen'),('Geen werk')");

        $this->addSql("INSERT INTO tw_regio (`label`) VALUES ('Overig/onduidelijk')");


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
