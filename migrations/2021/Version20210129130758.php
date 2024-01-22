<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210129130758 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE clip_afsluitredenen_vrijwilligers (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_deelnames (id INT AUTO_INCREMENT NOT NULL, clip_vrijwilliger_id INT NOT NULL, overig VARCHAR(255) DEFAULT NULL, datum DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, mwTraining_id INT NOT NULL, INDEX IDX_BDA50576459F3233 (mwTraining_id), INDEX IDX_BDA50576B280D297 (clip_vrijwilliger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_vrijwilligers (id INT AUTO_INCREMENT NOT NULL, vrijwilliger_id INT NOT NULL, binnen_via_id INT DEFAULT NULL, afsluitreden_id INT DEFAULT NULL, medewerker_id INT NOT NULL, aanmelddatum DATE NOT NULL, afsluitdatum DATE DEFAULT NULL, stagiair TINYINT(1) NOT NULL, startdatum DATE DEFAULT NULL, notitieIntake VARCHAR(255) DEFAULT NULL, datumNotitieIntake DATETIME DEFAULT NULL, trainingOverig VARCHAR(255) DEFAULT NULL, trainingOverigDatum VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, medewerkerLocatie_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_E0E2570B76B43BDC (vrijwilliger_id), INDEX IDX_E0E2570B4C676E6B (binnen_via_id), INDEX IDX_E0E2570BCA12F7AE (afsluitreden_id), INDEX IDX_E0E2570BEA9C84FE (medewerkerLocatie_id), INDEX IDX_E0E2570B3D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_vrijwilliger_locatie (vrijwilliger_id INT NOT NULL, locatie_id INT NOT NULL, INDEX IDX_96F1920576B43BDC (vrijwilliger_id), INDEX IDX_96F192054947630C (locatie_id), PRIMARY KEY(vrijwilliger_id, locatie_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_vrijwilliger_memo (vrijwilliger_id INT NOT NULL, memo_id INT NOT NULL, INDEX IDX_DDCB9E0D76B43BDC (vrijwilliger_id), UNIQUE INDEX UNIQ_DDCB9E0DB4D32439 (memo_id), PRIMARY KEY(vrijwilliger_id, memo_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_vrijwilliger_document (vrijwilliger_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_74EC4DF876B43BDC (vrijwilliger_id), UNIQUE INDEX UNIQ_74EC4DF8C33F7837 (document_id), PRIMARY KEY(vrijwilliger_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE clip_training (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql("
INSERT IGNORE INTO `clip_training` (`id`, `naam`, `active`) VALUES(1, 'Omgaan met verbale agressie (1)', 1);
INSERT IGNORE INTO `clip_training` (`id`, `naam`, `active`) VALUES(2, 'Werken met groepen', 1);
INSERT IGNORE INTO `clip_training` (`id`, `naam`, `active`) VALUES(3, 'Overig', 1)");

        $this->addSql('CREATE TABLE clip_binnen_via (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE clip_memos (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, datum DATETIME NOT NULL, onderwerp VARCHAR(255) NOT NULL, memo LONGTEXT NOT NULL, intake TINYINT(1) NOT NULL, INDEX IDX_BB25CF7C3D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clip_memos ADD CONSTRAINT FK_BB25CF7C3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');

        $this->addSql('ALTER TABLE clip_deelnames ADD CONSTRAINT FK_BDA50576459F3233 FOREIGN KEY (mwTraining_id) REFERENCES clip_training (id)');
        $this->addSql('ALTER TABLE clip_deelnames ADD CONSTRAINT FK_BDA50576B280D297 FOREIGN KEY (clip_vrijwilliger_id) REFERENCES clip_vrijwilligers (id)');
        $this->addSql('ALTER TABLE clip_vrijwilligers ADD CONSTRAINT FK_E0E2570B76B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES vrijwilligers (id)');
        $this->addSql('ALTER TABLE clip_vrijwilligers ADD CONSTRAINT FK_E0E2570B4C676E6B FOREIGN KEY (binnen_via_id) REFERENCES clip_binnen_via (id)');
        $this->addSql('ALTER TABLE clip_vrijwilligers ADD CONSTRAINT FK_E0E2570BCA12F7AE FOREIGN KEY (afsluitreden_id) REFERENCES clip_afsluitredenen_vrijwilligers (id)');
        $this->addSql('ALTER TABLE clip_vrijwilligers ADD CONSTRAINT FK_E0E2570BEA9C84FE FOREIGN KEY (medewerkerLocatie_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE clip_vrijwilligers ADD CONSTRAINT FK_E0E2570B3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE clip_vrijwilliger_locatie ADD CONSTRAINT FK_96F1920576B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES clip_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clip_vrijwilliger_locatie ADD CONSTRAINT FK_96F192054947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clip_vrijwilliger_memo ADD CONSTRAINT FK_DDCB9E0D76B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES clip_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clip_vrijwilliger_memo ADD CONSTRAINT FK_DDCB9E0DB4D32439 FOREIGN KEY (memo_id) REFERENCES clip_memos (id)');
        $this->addSql('ALTER TABLE clip_vrijwilliger_document ADD CONSTRAINT FK_74EC4DF876B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES clip_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clip_vrijwilliger_document ADD CONSTRAINT FK_74EC4DF8C33F7837 FOREIGN KEY (document_id) REFERENCES clip_documenten (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clip_vrijwilligers DROP FOREIGN KEY FK_E0E2570BCA12F7AE');
        $this->addSql('ALTER TABLE clip_deelnames DROP FOREIGN KEY FK_BDA50576B280D297');
        $this->addSql('ALTER TABLE clip_vrijwilliger_locatie DROP FOREIGN KEY FK_96F1920576B43BDC');
        $this->addSql('ALTER TABLE clip_vrijwilliger_memo DROP FOREIGN KEY FK_DDCB9E0D76B43BDC');
        $this->addSql('ALTER TABLE clip_vrijwilliger_document DROP FOREIGN KEY FK_74EC4DF876B43BDC');
        $this->addSql('ALTER TABLE clip_deelnames DROP FOREIGN KEY FK_BDA50576459F3233');
        $this->addSql('ALTER TABLE clip_vrijwilligers DROP FOREIGN KEY FK_E0E2570B4C676E6B');

        $this->addSql('DROP TABLE clip_afsluitredenen_vrijwilligers');
        $this->addSql('DROP TABLE clip_deelnames');
        $this->addSql('DROP TABLE clip_vrijwilligers');
        $this->addSql('DROP TABLE clip_vrijwilliger_locatie');
        $this->addSql('DROP TABLE clip_vrijwilliger_memo');
        $this->addSql('DROP TABLE clip_vrijwilliger_document');
        $this->addSql('DROP TABLE clip_training');
        $this->addSql('DROP TABLE clip_binnen_via');
        $this->addSql('DROP TABLE clip_memos');
    }
}
