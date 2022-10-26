<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210115080234 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mw_afsluitredenen_vrijwilligers (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_deelnames (id INT AUTO_INCREMENT NOT NULL, mw_vrijwilliger_id INT NOT NULL, overig VARCHAR(255) DEFAULT NULL, datum DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, mwTraining_id INT NOT NULL, INDEX IDX_59035D7541D2A6EF (mwTraining_id), INDEX IDX_59035D75B280D297 (mw_vrijwilliger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_vrijwilligers (id INT AUTO_INCREMENT NOT NULL, vrijwilliger_id INT NOT NULL, binnen_via_id INT DEFAULT NULL, afsluitreden_id INT DEFAULT NULL, medewerker_id INT NOT NULL, aanmelddatum DATE NOT NULL, afsluitdatum DATE DEFAULT NULL, stagiair TINYINT(1) NOT NULL, startdatum DATE DEFAULT NULL, notitieIntake VARCHAR(255) DEFAULT NULL, datumNotitieIntake DATETIME DEFAULT NULL, trainingOverig VARCHAR(255) DEFAULT NULL, trainingOverigDatum VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, medewerkerLocatie_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_CFC2BAE376B43BDC (vrijwilliger_id), INDEX IDX_CFC2BAE34C676E6B (binnen_via_id), INDEX IDX_CFC2BAE3CA12F7AE (afsluitreden_id), INDEX IDX_CFC2BAE3EA9C84FE (medewerkerLocatie_id), INDEX IDX_CFC2BAE33D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_vrijwilliger_locatie (vrijwilliger_id INT NOT NULL, locatie_id INT NOT NULL, INDEX IDX_35F4E24576B43BDC (vrijwilliger_id), INDEX IDX_35F4E2454947630C (locatie_id), PRIMARY KEY(vrijwilliger_id, locatie_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_vrijwilliger_memo (vrijwilliger_id INT NOT NULL, memo_id INT NOT NULL, INDEX IDX_516FADD276B43BDC (vrijwilliger_id), UNIQUE INDEX UNIQ_516FADD2B4D32439 (memo_id), PRIMARY KEY(vrijwilliger_id, memo_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_vrijwilliger_document (vrijwilliger_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_293091876B43BDC (vrijwilliger_id), UNIQUE INDEX UNIQ_2930918C33F7837 (document_id), PRIMARY KEY(vrijwilliger_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_binnen_via (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mw_deelnames ADD CONSTRAINT FK_59035D7541D2A6EF FOREIGN KEY (mwTraining_id) REFERENCES mw_training (id)');
        $this->addSql('ALTER TABLE mw_deelnames ADD CONSTRAINT FK_59035D75B280D297 FOREIGN KEY (mw_vrijwilliger_id) REFERENCES mw_vrijwilligers (id)');
        $this->addSql('ALTER TABLE mw_vrijwilligers ADD CONSTRAINT FK_CFC2BAE376B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES vrijwilligers (id)');
        $this->addSql('ALTER TABLE mw_vrijwilligers ADD CONSTRAINT FK_CFC2BAE34C676E6B FOREIGN KEY (binnen_via_id) REFERENCES mw_binnen_via (id)');
        $this->addSql('ALTER TABLE mw_vrijwilligers ADD CONSTRAINT FK_CFC2BAE3CA12F7AE FOREIGN KEY (afsluitreden_id) REFERENCES mw_afsluitredenen_vrijwilligers (id)');
        $this->addSql('ALTER TABLE mw_vrijwilligers ADD CONSTRAINT FK_CFC2BAE3EA9C84FE FOREIGN KEY (medewerkerLocatie_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE mw_vrijwilligers ADD CONSTRAINT FK_CFC2BAE33D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE mw_vrijwilliger_locatie ADD CONSTRAINT FK_35F4E24576B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES mw_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_vrijwilliger_locatie ADD CONSTRAINT FK_35F4E2454947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_vrijwilliger_memo ADD CONSTRAINT FK_516FADD276B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES mw_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_vrijwilliger_memo ADD CONSTRAINT FK_516FADD2B4D32439 FOREIGN KEY (memo_id) REFERENCES mw_memos (id)');
        $this->addSql('ALTER TABLE mw_vrijwilliger_document ADD CONSTRAINT FK_293091876B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES mw_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_vrijwilliger_document ADD CONSTRAINT FK_2930918C33F7837 FOREIGN KEY (document_id) REFERENCES mw_documenten (id) ON DELETE CASCADE');


        $this->addSql('CREATE TABLE mw_training (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql("
INSERT IGNORE INTO `mw_training` (`id`, `naam`, `active`) VALUES(1, 'Omgaan met verbale agressie (1)', 1);
INSERT IGNORE INTO `mw_training` (`id`, `naam`, `active`) VALUES(2, 'Werken met groepen', 1);
INSERT IGNORE INTO `mw_training` (`id`, `naam`, `active`) VALUES(3, 'Overig', 1)");


        $this->addSql('ALTER TABLE mw_deelnames DROP FOREIGN KEY FK_59035D7541D2A6EF');
        $this->addSql('DROP INDEX IDX_59035D7541D2A6EF ON mw_deelnames');
        $this->addSql('ALTER TABLE mw_deelnames CHANGE mwTraining_id mwTraining_id INT NOT NULL');
        $this->addSql('ALTER TABLE mw_deelnames ADD CONSTRAINT FK_59035D75459F3233 FOREIGN KEY (mwTraining_id) REFERENCES mw_training (id)');

        $this->addSql('CREATE INDEX IDX_59035D75459F3233 ON mw_deelnames (mwTraining_id)');

        //fix earlier 1:1 to N:N
        $this->addSql("INSERT IGNORE INTO inloop_vrijwilliger_locatie (vrijwilliger_id, locatie_id)
                        SELECT id, locatie_id FROM `inloop_vrijwilligers` WHERE locatie_id IS NOT NULL");
        $this->addSql("ALTER TABLE inloop_vrijwilligers DROP COLUMN locatie_id");
        $this->addSql("ALTER TABLE inloop_vrijwilligers DROP FOREIGN KEY FK_561104804947630C");
        $this->addSql("ALTER TABLE `inloop_vrijwilligers` DROP INDEX `IDX_561104804947630C`");
    }

     public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mw_deelnames DROP FOREIGN KEY FK_59035D75459F3233');
        $this->addSql('DROP INDEX IDX_59035D75459F3233 ON mw_deelnames');


        $this->addSql('DROP TABLE mw_training');

        $this->addSql('ALTER TABLE mw_vrijwilligers DROP FOREIGN KEY FK_CFC2BAE3CA12F7AE');
        $this->addSql('ALTER TABLE mw_deelnames DROP FOREIGN KEY FK_59035D75B280D297');
        $this->addSql('ALTER TABLE mw_vrijwilliger_locatie DROP FOREIGN KEY FK_35F4E24576B43BDC');
        $this->addSql('ALTER TABLE mw_vrijwilliger_memo DROP FOREIGN KEY FK_516FADD276B43BDC');
        $this->addSql('ALTER TABLE mw_vrijwilliger_document DROP FOREIGN KEY FK_293091876B43BDC');
        $this->addSql('ALTER TABLE mw_vrijwilligers DROP FOREIGN KEY FK_CFC2BAE34C676E6B');


        $this->addSql('DROP TABLE mw_afsluitredenen_vrijwilligers');
        $this->addSql('DROP TABLE mw_deelnames');
        $this->addSql('DROP TABLE mw_vrijwilligers');
        $this->addSql('DROP TABLE mw_vrijwilliger_locatie');
        $this->addSql('DROP TABLE mw_vrijwilliger_memo');
        $this->addSql('DROP TABLE mw_vrijwilliger_document');
        $this->addSql('DROP TABLE mw_binnen_via');


    }
}
