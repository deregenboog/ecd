<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210916130456 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tw_huisdieren (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tw_traplopen (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tw_moscreening (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tw_softdrugs (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tw_regio (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tw_roken (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tw_ritme (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tw_dagbesteding (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tw_inschrijvingwoningnet (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, `order` INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE tw_deelnemers ADD dagbesteding_id INT DEFAULT NULL, ADD ritme_id INT DEFAULT NULL, ADD huisdieren_id INT DEFAULT NULL, ADD roken_id INT DEFAULT NULL, ADD softdrugs_id INT DEFAULT NULL, ADD traplopen_id INT DEFAULT NULL, ADD bindingRegio_id INT DEFAULT NULL, ADD moScreening_id INT DEFAULT NULL, ADD inschrijvingWoningnet_id INT DEFAULT NULL ');
        $this->addSql('ALTER TABLE `tw_deelnemers` ADD `begeleider` VARCHAR(255) NULL AFTER `ambulantOndersteuner_id`');

        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E4317256A90F3026 FOREIGN KEY (bindingRegio_id) REFERENCES tw_regio (id)');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E4317256F9E2779E FOREIGN KEY (dagbesteding_id) REFERENCES tw_dagbesteding (id)');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E4317256F190A78A FOREIGN KEY (ritme_id) REFERENCES tw_ritme (id)');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E431725693EEEFEC FOREIGN KEY (huisdieren_id) REFERENCES tw_huisdieren (id)');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E431725687B1EE3E FOREIGN KEY (roken_id) REFERENCES tw_roken (id)');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E431725625BA5A47 FOREIGN KEY (softdrugs_id) REFERENCES tw_softdrugs (id)');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E4317256D6E2DB5B FOREIGN KEY (traplopen_id) REFERENCES tw_traplopen (id)');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E4317256D00EBD14 FOREIGN KEY (moScreening_id) REFERENCES tw_moscreening (id)');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E4317256D00EBD32 FOREIGN KEY (inschrijvingWoningnet_id) REFERENCES tw_inschrijvingwoningnet (id)');

        $this->addSql('CREATE INDEX IDX_E4317256A90F3026 ON tw_deelnemers (bindingRegio_id)');
        $this->addSql('CREATE INDEX IDX_E4317256F9E2779E ON tw_deelnemers (dagbesteding_id)');
        $this->addSql('CREATE INDEX IDX_E4317256F190A78A ON tw_deelnemers (ritme_id)');
        $this->addSql('CREATE INDEX IDX_E431725693EEEFEC ON tw_deelnemers (huisdieren_id)');
        $this->addSql('CREATE INDEX IDX_E431725687B1EE3E ON tw_deelnemers (roken_id)');
        $this->addSql('CREATE INDEX IDX_E431725625BA5A47 ON tw_deelnemers (softdrugs_id)');
        $this->addSql('CREATE INDEX IDX_E4317256D6E2DB5B ON tw_deelnemers (traplopen_id)');
        $this->addSql('CREATE INDEX IDX_E4317256D00EBD14 ON tw_deelnemers (moScreening_id)');
        $this->addSql('CREATE INDEX IDX_E4317256D00EBD32 ON tw_deelnemers (inschrijvingWoningnet_id)');

        $this->addSql("INSERT INTO tw_regio (label) VALUES ('Aalsmeer'),('Amstelveen'),('Amsterdam'),('Beverwijk'),
                            ('Bloemendaal'),('Diemen'),('Haarlemmermeer'),('Heemskerk'),('Heemstede'),('Ouder Amstel'),('Uitgeest'),
                            ('Uithoorn'),('Velsen'),('Weesp'),('Zaanstad'),('Zandvoort')");

        $this->addSql("INSERT INTO tw_dagbesteding (label) VALUES ('Veel thuis (niet of nauwelijks werkend'),('Tussenin'),('Niet veel thuis')");
        $this->addSql("INSERT INTO tw_ritme (label) VALUES ('Ochtendmens'),('Normaal'),('Avondmens'),('Wisselend')");
        $this->addSql("INSERT INTO tw_huisdieren (label) VALUES ('Nee'),('Geen honden'), ('Geen katten'), ('Geen honden en geen katten')");
        $this->addSql("INSERT INTO tw_roken (label) VALUES ('Rookt'),('Rookt niet'),('Tegen roken')");
        $this->addSql("INSERT INTO tw_softdrugs (label) VALUES ('Gebruikt'),('Gebruikt niet'),('Tegen gebruikt')");
        $this->addSql("INSERT INTO tw_traplopen (label) VALUES ('Ja'), ('Nee')");
        $this->addSql("INSERT INTO tw_moscreening (label) VALUES ('Niet gescreend'),('Afwijzing voor MO'),('Beschikking voor MO')");

        $this->addSql("INSERT INTO tw_inschrijvingwoningnet (label,`order`) VALUES
            ('Geen inschrijving',2), ('Onbekende duur',4),('<1 jaar',6),
                                   ('1 jaar',10),
                                ('2 jaar',20),
                                ('3 jaar',30),
                                ('4 jaar',40),
                                ('5 jaar',50),
                                ('6 jaar',60),
                                ('7 jaar',70),
                                ('8 jaar',80),
                                ('9 jaar',90),
                                ('10 jaar',100),
                                ('11 jaar',110),
                                ('12 jaar',120),
                                ('13 jaar',130),
                                ('14 jaar',140),
                                ('15 jaar',150),
                                ('16 jaar',160),
                                ('17 jaar',170),
                                ('18 jaar',180),
                                ('19 jaar',190),
                                ('20 jaar',200),
                                ('>20 jaar',210)

        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E431725693EEEFEC');
        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E4317256D6E2DB5B');
        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E4317256D00EBD14');
        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E431725625BA5A47');
        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E4317256A90F3026');
        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E431725687B1EE3E');
        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E4317256F190A78A');
        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E4317256F9E2779E');
        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E4317256D00EBD32');

        $this->addSql('DROP TABLE tw_huisdieren');
        $this->addSql('DROP TABLE tw_traplopen');
        $this->addSql('DROP TABLE tw_moscreening');
        $this->addSql('DROP TABLE tw_softdrugs');
        $this->addSql('DROP TABLE tw_regio');
        $this->addSql('DROP TABLE tw_roken');
        $this->addSql('DROP TABLE tw_ritme');
        $this->addSql('DROP TABLE tw_dagbesteding');
        $this->addSql('DROP TABLE tw_inschrijvingWoningnet');

        $this->addSql('DROP INDEX IDX_E4317256A90F3026 ON tw_deelnemers');
        $this->addSql('DROP INDEX IDX_E4317256F9E2779E ON tw_deelnemers');
        $this->addSql('DROP INDEX IDX_E4317256F190A78A ON tw_deelnemers');
        $this->addSql('DROP INDEX IDX_E431725693EEEFEC ON tw_deelnemers');
        $this->addSql('DROP INDEX IDX_E431725687B1EE3E ON tw_deelnemers');
        $this->addSql('DROP INDEX IDX_E431725625BA5A47 ON tw_deelnemers');
        $this->addSql('DROP INDEX IDX_E4317256D6E2DB5B ON tw_deelnemers');
        $this->addSql('DROP INDEX IDX_E4317256D00EBD14 ON tw_deelnemers');
        $this->addSql('DROP INDEX IDX_E4317256D00EBD32 ON tw_deelnemers');

        $this->addSql('ALTER TABLE tw_deelnemers DROP dagbesteding_id, DROP ritme_id, DROP huisdieren_id, DROP roken_id, DROP softdrugs_id, DROP traplopen_id, DROP bindingRegio_id, DROP moScreening_id, DROP inschrijvingWoningnet_id');
    }
}
