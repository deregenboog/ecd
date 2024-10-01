<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924131740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('CREATE TABLE villa_afsluitredenen_slaper (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, `active` TINYINT(1) NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE villa_dossier_statussen ADD reden_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE villa_dossier_statussen ADD CONSTRAINT FK_12C44093D29703A5 FOREIGN KEY (reden_id) REFERENCES villa_afsluitredenen_slaper (id)');
        $this->addSql('CREATE INDEX IDX_12C44093D29703A5 ON villa_dossier_statussen (reden_id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE villa_dossier_statussen DROP FOREIGN KEY FK_12C44093D29703A5');


        $this->addSql('DROP TABLE villa_afsluitredenen_slaper');


        $this->addSql('DROP INDEX IDX_12C44093D29703A5 ON villa_dossier_statussen');
        $this->addSql('ALTER TABLE villa_dossier_statussen DROP reden_id');
        $this->addSql('CREATE UNIQUE INDEX datum ON villa_overnachtingen (datum, slaper_id)');

    }
}
