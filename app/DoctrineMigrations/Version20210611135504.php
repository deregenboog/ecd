<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210611135504 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE odp_locaties (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, datum_van DATE NOT NULL, datum_tot DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_afsluitredenen_vrijwilligers (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_deelnames (id INT AUTO_INCREMENT NOT NULL, odp_vrijwilliger_id INT NOT NULL, overig VARCHAR(255) DEFAULT NULL, datum DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, mwTraining_id INT NOT NULL, INDEX IDX_B0B3FDE1459F3233 (mwTraining_id), INDEX IDX_B0B3FDE19D1883DD (odp_vrijwilliger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_vrijwilligers (id INT AUTO_INCREMENT NOT NULL, vrijwilliger_id INT NOT NULL, binnen_via_id INT DEFAULT NULL, afsluitreden_id INT DEFAULT NULL, medewerker_id INT NOT NULL, aanmelddatum DATE NOT NULL, afsluitdatum DATE DEFAULT NULL, stagiair TINYINT(1) NOT NULL, startdatum DATE DEFAULT NULL, notitieIntake VARCHAR(255) DEFAULT NULL, datumNotitieIntake DATETIME DEFAULT NULL, trainingOverig VARCHAR(255) DEFAULT NULL, trainingOverigDatum VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, medewerkerLocatie_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_198B651476B43BDC (vrijwilliger_id), INDEX IDX_198B65144C676E6B (binnen_via_id), INDEX IDX_198B6514CA12F7AE (afsluitreden_id), INDEX IDX_198B6514EA9C84FE (medewerkerLocatie_id), INDEX IDX_198B65143D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_vrijwilliger_locatie (vrijwilliger_id INT NOT NULL, locatie_id INT NOT NULL, INDEX IDX_2199949576B43BDC (vrijwilliger_id), INDEX IDX_219994954947630C (locatie_id), PRIMARY KEY(vrijwilliger_id, locatie_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_vrijwilliger_memo (vrijwilliger_id INT NOT NULL, memo_id INT NOT NULL, INDEX IDX_8200726C76B43BDC (vrijwilliger_id), UNIQUE INDEX UNIQ_8200726CB4D32439 (memo_id), PRIMARY KEY(vrijwilliger_id, memo_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_vrijwilliger_document (vrijwilliger_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_8454B6BA76B43BDC (vrijwilliger_id), UNIQUE INDEX UNIQ_8454B6BAC33F7837 (document_id), PRIMARY KEY(vrijwilliger_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_training (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_binnen_via (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE odp_deelnames ADD CONSTRAINT FK_B0B3FDE1459F3233 FOREIGN KEY (mwTraining_id) REFERENCES odp_training (id)');
        $this->addSql('ALTER TABLE odp_deelnames ADD CONSTRAINT FK_B0B3FDE19D1883DD FOREIGN KEY (odp_vrijwilliger_id) REFERENCES odp_vrijwilligers (id)');
        $this->addSql('ALTER TABLE odp_vrijwilligers ADD CONSTRAINT FK_198B651476B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES vrijwilligers (id)');
        $this->addSql('ALTER TABLE odp_vrijwilligers ADD CONSTRAINT FK_198B65144C676E6B FOREIGN KEY (binnen_via_id) REFERENCES odp_binnen_via (id)');
        $this->addSql('ALTER TABLE odp_vrijwilligers ADD CONSTRAINT FK_198B6514CA12F7AE FOREIGN KEY (afsluitreden_id) REFERENCES odp_afsluitredenen_vrijwilligers (id)');
        $this->addSql('ALTER TABLE odp_vrijwilligers ADD CONSTRAINT FK_198B6514EA9C84FE FOREIGN KEY (medewerkerLocatie_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE odp_vrijwilligers ADD CONSTRAINT FK_198B65143D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE odp_vrijwilliger_locatie ADD CONSTRAINT FK_2199949576B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES odp_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odp_vrijwilliger_locatie ADD CONSTRAINT FK_219994954947630C FOREIGN KEY (locatie_id) REFERENCES odp_locaties (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odp_vrijwilliger_memo ADD CONSTRAINT FK_8200726C76B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES odp_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odp_vrijwilliger_memo ADD CONSTRAINT FK_8200726CB4D32439 FOREIGN KEY (memo_id) REFERENCES inloop_memos (id)');
        $this->addSql('ALTER TABLE odp_vrijwilliger_document ADD CONSTRAINT FK_8454B6BA76B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES odp_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odp_vrijwilliger_document ADD CONSTRAINT FK_8454B6BAC33F7837 FOREIGN KEY (document_id) REFERENCES odp_superdocumenten (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_vrijwilliger_locatie DROP FOREIGN KEY FK_219994954947630C');
        $this->addSql('ALTER TABLE odp_vrijwilligers DROP FOREIGN KEY FK_198B6514CA12F7AE');
        $this->addSql('ALTER TABLE odp_deelnames DROP FOREIGN KEY FK_B0B3FDE19D1883DD');
        $this->addSql('ALTER TABLE odp_vrijwilliger_locatie DROP FOREIGN KEY FK_2199949576B43BDC');
        $this->addSql('ALTER TABLE odp_vrijwilliger_memo DROP FOREIGN KEY FK_8200726C76B43BDC');
        $this->addSql('ALTER TABLE odp_vrijwilliger_document DROP FOREIGN KEY FK_8454B6BA76B43BDC');
        $this->addSql('ALTER TABLE odp_deelnames DROP FOREIGN KEY FK_B0B3FDE1459F3233');
        $this->addSql('ALTER TABLE odp_vrijwilligers DROP FOREIGN KEY FK_198B65144C676E6B');

        $this->addSql('DROP TABLE odp_locaties');
        $this->addSql('DROP TABLE odp_afsluitredenen_vrijwilligers');
        $this->addSql('DROP TABLE odp_deelnames');
        $this->addSql('DROP TABLE odp_vrijwilligers');
        $this->addSql('DROP TABLE odp_vrijwilliger_locatie');
        $this->addSql('DROP TABLE odp_vrijwilliger_memo');
        $this->addSql('DROP TABLE odp_vrijwilliger_document');
        $this->addSql('DROP TABLE odp_training');
        $this->addSql('DROP TABLE odp_binnen_via');
    }
}
