<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207095056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'sets up villa slapers and related entities';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('CREATE TABLE villa_dossier_statussen (id INT AUTO_INCREMENT NOT NULL, slaper_id INT NOT NULL, medewerker_id INT NOT NULL, datum DATE NOT NULL, aangemeldVia VARCHAR(255) DEFAULT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, class VARCHAR(255) NOT NULL, INDEX IDX_12C44093876498D2 (slaper_id), INDEX IDX_12C440933D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE villa_slapers (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, opmerking LONGTEXT DEFAULT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, appKlant_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_157EE015C217849 (appKlant_id), INDEX IDX_157EE013D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE villa_slapers ADD slaperType INT DEFAULT 1 NOT NULL, ADD contactpersoon VARCHAR(255) DEFAULT NULL, ADD contactgegevensContactpersoon VARCHAR(255) DEFAULT NULL, ADD redenSlapen LONGTEXT DEFAULT NULL');

        $this->addSql('CREATE TABLE villa_overnachtingen (id INT AUTO_INCREMENT NOT NULL, slaper_id INT NOT NULL, opmerking VARCHAR(255) DEFAULT NULL, datum DATE DEFAULT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_359057AC876498D2 (slaper_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE villa_dossier_statussen ADD CONSTRAINT FK_12C44093876498D2 FOREIGN KEY (slaper_id) REFERENCES villa_slapers (id)');
        $this->addSql('ALTER TABLE villa_dossier_statussen ADD CONSTRAINT FK_12C440933D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');


        $this->addSql('ALTER TABLE villa_slapers ADD CONSTRAINT FK_157EE015C217849 FOREIGN KEY (appKlant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE villa_slapers ADD CONSTRAINT FK_157EE013D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');


        $this->addSql('ALTER TABLE villa_overnachtingen ADD CONSTRAINT FK_359057AC876498D2 FOREIGN KEY (slaper_id) REFERENCES villa_slapers (id)');
        $this->addSql('ALTER TABLE `villa_overnachtingen` ADD UNIQUE (`datum`, `slaper_id`)');



    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE villa_dossier_statussen DROP FOREIGN KEY FK_12C44093876498D2');
        $this->addSql('ALTER TABLE villa_dossier_statussen DROP FOREIGN KEY FK_12C440933D707F64');
        $this->addSql('ALTER TABLE villa_slapers DROP FOREIGN KEY FK_157EE015C217849');
        $this->addSql('ALTER TABLE villa_slapers DROP FOREIGN KEY FK_157EE013D707F64');

        $this->addSql('ALTER TABLE villa_overnachtingen DROP FOREIGN KEY FK_359057AC876498D2');

        $this->addSql('DROP TABLE villa_overnachtingen');
        $this->addSql('DROP TABLE villa_dossier_statussen');
        $this->addSql('DROP TABLE villa_slapers');

    }
}
