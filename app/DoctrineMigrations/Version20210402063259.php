<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210402063259 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `klanten` DROP FOREIGN KEY `FK_F538C5BC815E1ED`; ALTER TABLE `klanten` ADD CONSTRAINT `FK_F538C5BC815E1ED` FOREIGN KEY (`laatste_registratie_id`) REFERENCES `registraties`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;');
        $this->addSql('ALTER TABLE `registraties_recent` DROP FOREIGN KEY `FK_B1AD39F05CD9765E`;ALTER TABLE `registraties_recent` ADD CONSTRAINT `FK_B1AD39F05CD9765E` FOREIGN KEY (`registratie_id`) REFERENCES `registraties`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
