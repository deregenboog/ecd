<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201109101551 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql("ALTER TABLE `klanten` ADD  CONSTRAINT `FK_F538C5BC815E1ED` FOREIGN KEY (`laatste_registratie_id`) REFERENCES `registraties`(`id`) ON DELETE SET NULL ON UPDATE SET NULL");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql("ALTER TABLE `klanten` ADD  CONSTRAINT `FK_F538C5BC815E1ED` FOREIGN KEY (`laatste_registratie_id`) REFERENCES `registraties`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT");
    }
}
