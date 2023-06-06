<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531092710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tables for LocatieType m2m on Locatie';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('CREATE TABLE locatie_type (id INT AUTO_INCREMENT NOT NULL, created DATETIME DEFAULT NULL, modified DATETIME DEFAULT NULL, naam VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inloop_locatie_locatietype (locatie_id INT NOT NULL, locatietype_id INT NOT NULL, INDEX IDX_12B01AF4947630C (locatie_id), INDEX IDX_12B01AF5FCF7087 (locatietype_id), PRIMARY KEY(locatie_id, locatietype_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inloop_locatie_locatietype ADD CONSTRAINT FK_12B01AF4947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inloop_locatie_locatietype ADD CONSTRAINT FK_12B01AF5FCF7087 FOREIGN KEY (locatietype_id) REFERENCES locatie_type (id) ON DELETE CASCADE');
        $this->addSql('INSERT INTO locatie_type (naam, created, modified) VALUES ("Inloop",CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
        $this->addSql('INSERT INTO locatie_type (naam, created, modified) VALUES ("Maatschappelijk werk",CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
        $this->addSql('INSERT INTO locatie_type (naam, created, modified) VALUES ("Wachtlijst",CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
        $this->addSql('INSERT INTO locatie_type (naam, created, modified) VALUES ("Virtueel",CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
        $this->addSql('INSERT INTO inloop_locatie_locatietype (locatie_id, locatietype_id) SELECT DISTINCT l.id, (SELECT id FROM locatie_type WHERE naam = "Wachtlijst") FROM locaties AS l WHERE wachtlijst = 1');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE inloop_locatie_locatietype DROP FOREIGN KEY FK_12B01AF4947630C');
        $this->addSql('ALTER TABLE inloop_locatie_locatietype DROP FOREIGN KEY FK_12B01AF5FCF7087');
        $this->addSql('DROP TABLE locatie_type');
        $this->addSql('DROP TABLE inloop_locatie_locatietype');
    }
}
