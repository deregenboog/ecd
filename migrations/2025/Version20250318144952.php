<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250318144952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE oekraine_binnen_via (id INT AUTO_INCREMENT NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, naam VARCHAR(255) NOT NULL, `active` TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_deelnames (id INT AUTO_INCREMENT NOT NULL, oekraine_vrijwilliger_id INT DEFAULT NULL, overig VARCHAR(255) DEFAULT NULL, datum DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, oekraineTraining_id INT DEFAULT NULL, INDEX IDX_7C25FF9A1CF3C3A (oekraineTraining_id), INDEX IDX_7C25FF96BE8E0B1 (oekraine_vrijwilliger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_training (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, `active` TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_vrijwilligers (id INT AUTO_INCREMENT NOT NULL, vrijwilliger_id INT NOT NULL, binnen_via_id INT DEFAULT NULL, afsluitreden_id INT DEFAULT NULL, medewerker_id INT NOT NULL, aanmelddatum DATE NOT NULL, afsluitdatum DATE DEFAULT NULL, stagiair TINYINT(1) NOT NULL, startdatum DATE DEFAULT NULL, notitieIntake VARCHAR(255) DEFAULT NULL, datumNotitieIntake DATE DEFAULT NULL, trainingOverig VARCHAR(255) DEFAULT NULL, trainingOverigDatum VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, medewerkerLocatie_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_35FBFDF276B43BDC (vrijwilliger_id), INDEX IDX_35FBFDF24C676E6B (binnen_via_id), INDEX IDX_35FBFDF2CA12F7AE (afsluitreden_id), INDEX IDX_35FBFDF2EA9C84FE (medewerkerLocatie_id), INDEX IDX_35FBFDF23D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_vrijwilliger_locatie (vrijwilliger_id INT NOT NULL, locatie_id INT NOT NULL, INDEX IDX_A3EA63DF76B43BDC (vrijwilliger_id), INDEX IDX_A3EA63DF4947630C (locatie_id), PRIMARY KEY(vrijwilliger_id, locatie_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_vrijwilliger_memo (vrijwilliger_id INT NOT NULL, memo_id INT NOT NULL, INDEX IDX_9FF390D576B43BDC (vrijwilliger_id), UNIQUE INDEX UNIQ_9FF390D5B4D32439 (memo_id), PRIMARY KEY(vrijwilliger_id, memo_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekraine_vrijwilliger_document (vrijwilliger_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_12DF6DC376B43BDC (vrijwilliger_id), UNIQUE INDEX UNIQ_12DF6DC3C33F7837 (document_id), PRIMARY KEY(vrijwilliger_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE oekraine_deelnames ADD CONSTRAINT FK_7C25FF9A1CF3C3A FOREIGN KEY (oekraineTraining_id) REFERENCES oekraine_training (id)');
        $this->addSql('ALTER TABLE oekraine_deelnames ADD CONSTRAINT FK_7C25FF96BE8E0B1 FOREIGN KEY (oekraine_vrijwilliger_id) REFERENCES oekraine_vrijwilligers (id)');
        $this->addSql('ALTER TABLE oekraine_vrijwilligers ADD CONSTRAINT FK_35FBFDF276B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES vrijwilligers (id)');
        $this->addSql('ALTER TABLE oekraine_vrijwilligers ADD CONSTRAINT FK_35FBFDF24C676E6B FOREIGN KEY (binnen_via_id) REFERENCES oekraine_binnen_via (id)');
        $this->addSql('ALTER TABLE oekraine_vrijwilligers ADD CONSTRAINT FK_35FBFDF2CA12F7AE FOREIGN KEY (afsluitreden_id) REFERENCES oekraine_afsluitredenen_vrijwilligers (id)');
        $this->addSql('ALTER TABLE oekraine_vrijwilligers ADD CONSTRAINT FK_35FBFDF2EA9C84FE FOREIGN KEY (medewerkerLocatie_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE oekraine_vrijwilligers ADD CONSTRAINT FK_35FBFDF23D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE oekraine_vrijwilliger_locatie ADD CONSTRAINT FK_A3EA63DF76B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES oekraine_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oekraine_vrijwilliger_locatie ADD CONSTRAINT FK_A3EA63DF4947630C FOREIGN KEY (locatie_id) REFERENCES oekraine_locaties (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oekraine_vrijwilliger_memo ADD CONSTRAINT FK_9FF390D576B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES oekraine_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oekraine_vrijwilliger_memo ADD CONSTRAINT FK_9FF390D5B4D32439 FOREIGN KEY (memo_id) REFERENCES inloop_memos (id)');
        $this->addSql('ALTER TABLE oekraine_vrijwilliger_document ADD CONSTRAINT FK_12DF6DC376B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES oekraine_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oekraine_vrijwilliger_document ADD CONSTRAINT FK_12DF6DC3C33F7837 FOREIGN KEY (document_id) REFERENCES oekraine_documenten (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE oekraine_deelnames DROP FOREIGN KEY FK_7C25FF9A1CF3C3A');
        $this->addSql('ALTER TABLE oekraine_deelnames DROP FOREIGN KEY FK_7C25FF96BE8E0B1');
        $this->addSql('ALTER TABLE oekraine_vrijwilligers DROP FOREIGN KEY FK_35FBFDF276B43BDC');
        $this->addSql('ALTER TABLE oekraine_vrijwilligers DROP FOREIGN KEY FK_35FBFDF24C676E6B');
        $this->addSql('ALTER TABLE oekraine_vrijwilligers DROP FOREIGN KEY FK_35FBFDF2CA12F7AE');
        $this->addSql('ALTER TABLE oekraine_vrijwilligers DROP FOREIGN KEY FK_35FBFDF2EA9C84FE');
        $this->addSql('ALTER TABLE oekraine_vrijwilligers DROP FOREIGN KEY FK_35FBFDF23D707F64');
        $this->addSql('ALTER TABLE oekraine_vrijwilliger_locatie DROP FOREIGN KEY FK_A3EA63DF76B43BDC');
        $this->addSql('ALTER TABLE oekraine_vrijwilliger_locatie DROP FOREIGN KEY FK_A3EA63DF4947630C');
        $this->addSql('ALTER TABLE oekraine_vrijwilliger_memo DROP FOREIGN KEY FK_9FF390D576B43BDC');
        $this->addSql('ALTER TABLE oekraine_vrijwilliger_memo DROP FOREIGN KEY FK_9FF390D5B4D32439');
        $this->addSql('ALTER TABLE oekraine_vrijwilliger_document DROP FOREIGN KEY FK_12DF6DC376B43BDC');
        $this->addSql('ALTER TABLE oekraine_vrijwilliger_document DROP FOREIGN KEY FK_12DF6DC3C33F7837');
        $this->addSql('DROP TABLE oekraine_binnen_via');
        $this->addSql('DROP TABLE oekraine_deelnames');
        $this->addSql('DROP TABLE oekraine_training');
        $this->addSql('DROP TABLE oekraine_vrijwilligers');
        $this->addSql('DROP TABLE oekraine_vrijwilliger_locatie');
        $this->addSql('DROP TABLE oekraine_vrijwilliger_memo');
        $this->addSql('DROP TABLE oekraine_vrijwilliger_document');
    }
}
