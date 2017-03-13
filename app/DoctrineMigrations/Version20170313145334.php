<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170313145334 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hs_memos (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, datum DATETIME NOT NULL, memo LONGTEXT NOT NULL, intake TINYINT(1) NOT NULL, INDEX IDX_4048AFA33D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_registraties (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, datum DATE NOT NULL, start TIME NOT NULL, eind TIME NOT NULL, reiskosten DOUBLE PRECISION DEFAULT NULL, hsKlus_id INT DEFAULT NULL, hsFactuur_id INT DEFAULT NULL, hsVrijwilliger_id INT NOT NULL, hsActiviteit_id INT NOT NULL, INDEX IDX_62041BC2CBEF332C (hsKlus_id), INDEX IDX_62041BC29E5FDEF3 (hsFactuur_id), INDEX IDX_62041BC28316E863 (hsVrijwilliger_id), INDEX IDX_62041BC275C8B1CB (hsActiviteit_id), INDEX IDX_62041BC23D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_facturen (id INT AUTO_INCREMENT NOT NULL, nummer VARCHAR(255) NOT NULL, datum DATE NOT NULL, betreft VARCHAR(255) NOT NULL, bedrag NUMERIC(10, 2) NOT NULL, hsKlus_id INT DEFAULT NULL, INDEX IDX_31669C16CBEF332C (hsKlus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_klanten (id INT AUTO_INCREMENT NOT NULL, klant_id INT NOT NULL, inschrijving DATE NOT NULL, uitschrijving DATE DEFAULT NULL, laatsteContact DATE DEFAULT NULL, actief TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, onHold TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_CC6284A3C427B2F (klant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hsklant_hsmemo (hsklant_id INT NOT NULL, hsmemo_id INT NOT NULL, INDEX IDX_EDD75F39B1F477A4 (hsklant_id), UNIQUE INDEX UNIQ_EDD75F39AD25A26 (hsmemo_id), PRIMARY KEY(hsklant_id, hsmemo_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_betalingen (id INT AUTO_INCREMENT NOT NULL, referentie VARCHAR(255) DEFAULT NULL, datum DATE NOT NULL, info LONGTEXT DEFAULT NULL, bedrag NUMERIC(10, 2) NOT NULL, hsFactuur_id INT NOT NULL, INDEX IDX_EADEA9FF9E5FDEF3 (hsFactuur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_declaratie_categorieen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_vrijwilligers (id INT AUTO_INCREMENT NOT NULL, vrijwilliger_id INT NOT NULL, inschrijving DATE NOT NULL, uitschrijving DATE DEFAULT NULL, dragend TINYINT(1) NOT NULL, rijbewijs TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, UNIQUE INDEX UNIQ_3FE7029676B43BDC (vrijwilliger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hsvrijwilliger_hsmemo (hsvrijwilliger_id INT NOT NULL, hsmemo_id INT NOT NULL, INDEX IDX_7219C0B1BD3D6FD (hsvrijwilliger_id), UNIQUE INDEX UNIQ_7219C0B1AD25A26 (hsmemo_id), PRIMARY KEY(hsvrijwilliger_id, hsmemo_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_declaraties (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, datum DATE NOT NULL, info LONGTEXT NOT NULL, bedrag DOUBLE PRECISION NOT NULL, hsKlus_id INT DEFAULT NULL, hsFactuur_id INT DEFAULT NULL, hsDeclaratieCategorie_id INT NOT NULL, INDEX IDX_AF23D292CBEF332C (hsKlus_id), INDEX IDX_AF23D2929E5FDEF3 (hsFactuur_id), INDEX IDX_AF23D292E72D508D (hsDeclaratieCategorie_id), INDEX IDX_AF23D2923D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_klussen (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, datum DATE NOT NULL, startdatum DATE NOT NULL, einddatum DATE DEFAULT NULL, onHold TINYINT(1) NOT NULL, hsKlant_id INT DEFAULT NULL, hsActiviteit_id INT NOT NULL, INDEX IDX_3E9A80CF488215F2 (hsKlant_id), INDEX IDX_3E9A80CF75C8B1CB (hsActiviteit_id), INDEX IDX_3E9A80CF3D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hsklus_hsvrijwilliger (hsklus_id INT NOT NULL, hsvrijwilliger_id INT NOT NULL, INDEX IDX_E61C44284520AB0 (hsklus_id), INDEX IDX_E61C4428BD3D6FD (hsvrijwilliger_id), PRIMARY KEY(hsklus_id, hsvrijwilliger_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hsklus_hsmemo (hsklus_id INT NOT NULL, hsmemo_id INT NOT NULL, INDEX IDX_2DD3389E4520AB0 (hsklus_id), UNIQUE INDEX UNIQ_2DD3389EAD25A26 (hsmemo_id), PRIMARY KEY(hsklus_id, hsmemo_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_activiteiten (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hs_memos ADD CONSTRAINT FK_4048AFA33D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE hs_registraties ADD CONSTRAINT FK_62041BC2CBEF332C FOREIGN KEY (hsKlus_id) REFERENCES hs_klussen (id)');
        $this->addSql('ALTER TABLE hs_registraties ADD CONSTRAINT FK_62041BC29E5FDEF3 FOREIGN KEY (hsFactuur_id) REFERENCES hs_facturen (id)');
        $this->addSql('ALTER TABLE hs_registraties ADD CONSTRAINT FK_62041BC28316E863 FOREIGN KEY (hsVrijwilliger_id) REFERENCES hs_vrijwilligers (id)');
        $this->addSql('ALTER TABLE hs_registraties ADD CONSTRAINT FK_62041BC275C8B1CB FOREIGN KEY (hsActiviteit_id) REFERENCES hs_activiteiten (id)');
        $this->addSql('ALTER TABLE hs_registraties ADD CONSTRAINT FK_62041BC23D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE hs_facturen ADD CONSTRAINT FK_31669C16CBEF332C FOREIGN KEY (hsKlus_id) REFERENCES hs_klussen (id)');
        $this->addSql('ALTER TABLE hs_klanten ADD CONSTRAINT FK_CC6284A3C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE hsklant_hsmemo ADD CONSTRAINT FK_EDD75F39B1F477A4 FOREIGN KEY (hsklant_id) REFERENCES hs_klanten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hsklant_hsmemo ADD CONSTRAINT FK_EDD75F39AD25A26 FOREIGN KEY (hsmemo_id) REFERENCES hs_memos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_betalingen ADD CONSTRAINT FK_EADEA9FF9E5FDEF3 FOREIGN KEY (hsFactuur_id) REFERENCES hs_facturen (id)');
        $this->addSql('ALTER TABLE hs_vrijwilligers ADD CONSTRAINT FK_3FE7029676B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES vrijwilligers (id)');
        $this->addSql('ALTER TABLE hsvrijwilliger_hsmemo ADD CONSTRAINT FK_7219C0B1BD3D6FD FOREIGN KEY (hsvrijwilliger_id) REFERENCES hs_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hsvrijwilliger_hsmemo ADD CONSTRAINT FK_7219C0B1AD25A26 FOREIGN KEY (hsmemo_id) REFERENCES hs_memos (id)');
        $this->addSql('ALTER TABLE hs_declaraties ADD CONSTRAINT FK_AF23D292CBEF332C FOREIGN KEY (hsKlus_id) REFERENCES hs_klussen (id)');
        $this->addSql('ALTER TABLE hs_declaraties ADD CONSTRAINT FK_AF23D2929E5FDEF3 FOREIGN KEY (hsFactuur_id) REFERENCES hs_facturen (id)');
        $this->addSql('ALTER TABLE hs_declaraties ADD CONSTRAINT FK_AF23D292E72D508D FOREIGN KEY (hsDeclaratieCategorie_id) REFERENCES hs_declaratie_categorieen (id)');
        $this->addSql('ALTER TABLE hs_declaraties ADD CONSTRAINT FK_AF23D2923D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE hs_klussen ADD CONSTRAINT FK_3E9A80CF488215F2 FOREIGN KEY (hsKlant_id) REFERENCES hs_klanten (id)');
        $this->addSql('ALTER TABLE hs_klussen ADD CONSTRAINT FK_3E9A80CF75C8B1CB FOREIGN KEY (hsActiviteit_id) REFERENCES hs_activiteiten (id)');
        $this->addSql('ALTER TABLE hs_klussen ADD CONSTRAINT FK_3E9A80CF3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE hsklus_hsvrijwilliger ADD CONSTRAINT FK_E61C44284520AB0 FOREIGN KEY (hsklus_id) REFERENCES hs_klussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hsklus_hsvrijwilliger ADD CONSTRAINT FK_E61C4428BD3D6FD FOREIGN KEY (hsvrijwilliger_id) REFERENCES hs_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hsklus_hsmemo ADD CONSTRAINT FK_2DD3389E4520AB0 FOREIGN KEY (hsklus_id) REFERENCES hs_klussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hsklus_hsmemo ADD CONSTRAINT FK_2DD3389EAD25A26 FOREIGN KEY (hsmemo_id) REFERENCES hs_memos (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
