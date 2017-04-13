<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170511090457 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hs_klanten (id INT AUTO_INCREMENT NOT NULL, geslacht_id INT NOT NULL, medewerker_id INT NOT NULL, werkgebied VARCHAR(255) DEFAULT NULL, inschrijving DATE NOT NULL, uitschrijving DATE DEFAULT NULL, laatsteContact DATE DEFAULT NULL, actief TINYINT(1) NOT NULL, onHold TINYINT(1) NOT NULL, bewindvoerder LONGTEXT DEFAULT NULL, saldo NUMERIC(10, 2) NOT NULL, voornaam VARCHAR(255) DEFAULT NULL, roepnaam VARCHAR(255) DEFAULT NULL, tussenvoegsel VARCHAR(255) DEFAULT NULL, achternaam VARCHAR(255) NOT NULL, adres VARCHAR(255) DEFAULT NULL, postcode VARCHAR(255) DEFAULT NULL, plaats VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, mobiel VARCHAR(255) DEFAULT NULL, telefoon VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_CC6284A1C729A47 (geslacht_id), INDEX IDX_CC6284A3D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_klant_memo (klant_id INT NOT NULL, memo_id INT NOT NULL, INDEX IDX_177077063C427B2F (klant_id), UNIQUE INDEX UNIQ_17707706B4D32439 (memo_id), PRIMARY KEY(klant_id, memo_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_klant_document (klant_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_795E7D0B3C427B2F (klant_id), UNIQUE INDEX UNIQ_795E7D0BC33F7837 (document_id), PRIMARY KEY(klant_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_arbeiders (id INT AUTO_INCREMENT NOT NULL, inschrijving DATE NOT NULL, uitschrijving DATE DEFAULT NULL, rijbewijs TINYINT(1) DEFAULT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_vrijwilligers (id INT NOT NULL, vrijwilliger_id INT NOT NULL, UNIQUE INDEX UNIQ_3FE7029676B43BDC (vrijwilliger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_vrijwilliger_memo (vrijwilliger_id INT NOT NULL, memo_id INT NOT NULL, INDEX IDX_938D702F76B43BDC (vrijwilliger_id), UNIQUE INDEX UNIQ_938D702FB4D32439 (memo_id), PRIMARY KEY(vrijwilliger_id, memo_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_vrijwilliger_document (vrijwilliger_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_EAEC53F376B43BDC (vrijwilliger_id), UNIQUE INDEX UNIQ_EAEC53F3C33F7837 (document_id), PRIMARY KEY(vrijwilliger_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_documenten (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, naam VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_87CDF0443D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_declaraties (id INT AUTO_INCREMENT NOT NULL, klus_id INT DEFAULT NULL, factuur_id INT DEFAULT NULL, medewerker_id INT NOT NULL, datum DATE NOT NULL, info LONGTEXT NOT NULL, bedrag DOUBLE PRECISION NOT NULL, declaratieCategorie_id INT NOT NULL, INDEX IDX_AF23D292BA5374AF (klus_id), INDEX IDX_AF23D292C35D3E (factuur_id), INDEX IDX_AF23D2921EE52B26 (declaratieCategorie_id), INDEX IDX_AF23D2923D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_declaratie_document (declaratie_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_9E1A25FE6AE7FC36 (declaratie_id), UNIQUE INDEX UNIQ_9E1A25FEC33F7837 (document_id), PRIMARY KEY(declaratie_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_dienstverleners (id INT NOT NULL, klant_id INT NOT NULL, UNIQUE INDEX UNIQ_4949548D3C427B2F (klant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_dienstverlener_memo (dienstverlener_id INT NOT NULL, memo_id INT NOT NULL, INDEX IDX_F546B7DDA1690166 (dienstverlener_id), UNIQUE INDEX UNIQ_F546B7DDB4D32439 (memo_id), PRIMARY KEY(dienstverlener_id, memo_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_dienstverlener_document (dienstverlener_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_DEBCC7F2A1690166 (dienstverlener_id), UNIQUE INDEX UNIQ_DEBCC7F2C33F7837 (document_id), PRIMARY KEY(dienstverlener_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_facturen (id INT AUTO_INCREMENT NOT NULL, klant_id INT DEFAULT NULL, nummer VARCHAR(255) NOT NULL, datum DATE NOT NULL, betreft VARCHAR(255) NOT NULL, bedrag NUMERIC(10, 2) NOT NULL, INDEX IDX_31669C163C427B2F (klant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_factuur_klus (factuur_id INT NOT NULL, klus_id INT NOT NULL, INDEX IDX_B3DD2838C35D3E (factuur_id), INDEX IDX_B3DD2838BA5374AF (klus_id), PRIMARY KEY(factuur_id, klus_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_registraties (id INT AUTO_INCREMENT NOT NULL, klus_id INT DEFAULT NULL, factuur_id INT DEFAULT NULL, arbeider_id INT NOT NULL, activiteit_id INT NOT NULL, medewerker_id INT NOT NULL, datum DATE NOT NULL, start TIME NOT NULL, eind TIME NOT NULL, reiskosten DOUBLE PRECISION DEFAULT NULL, INDEX IDX_62041BC2BA5374AF (klus_id), INDEX IDX_62041BC2C35D3E (factuur_id), INDEX IDX_62041BC26650623E (arbeider_id), INDEX IDX_62041BC25A8A0A1 (activiteit_id), INDEX IDX_62041BC23D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_declaratie_categorieen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_memos (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, datum DATETIME NOT NULL, memo LONGTEXT NOT NULL, intake TINYINT(1) NOT NULL, INDEX IDX_4048AFA33D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_klussen (id INT AUTO_INCREMENT NOT NULL, klant_id INT DEFAULT NULL, activiteit_id INT NOT NULL, medewerker_id INT NOT NULL, datum DATE NOT NULL, startdatum DATE NOT NULL, einddatum DATE DEFAULT NULL, onHold TINYINT(1) NOT NULL, INDEX IDX_3E9A80CF3C427B2F (klant_id), INDEX IDX_3E9A80CF5A8A0A1 (activiteit_id), INDEX IDX_3E9A80CF3D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_klus_dienstverlener (klus_id INT NOT NULL, dienstverlener_id INT NOT NULL, INDEX IDX_70F96EFBBA5374AF (klus_id), INDEX IDX_70F96EFBA1690166 (dienstverlener_id), PRIMARY KEY(klus_id, dienstverlener_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_klus_vrijwilliger (klus_id INT NOT NULL, vrijwilliger_id INT NOT NULL, INDEX IDX_6E1EDAA1BA5374AF (klus_id), INDEX IDX_6E1EDAA176B43BDC (vrijwilliger_id), PRIMARY KEY(klus_id, vrijwilliger_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_klus_memo (klus_id INT NOT NULL, memo_id INT NOT NULL, INDEX IDX_208D08ECBA5374AF (klus_id), UNIQUE INDEX UNIQ_208D08ECB4D32439 (memo_id), PRIMARY KEY(klus_id, memo_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_activiteiten (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hs_betalingen (id INT AUTO_INCREMENT NOT NULL, factuur_id INT NOT NULL, referentie VARCHAR(255) DEFAULT NULL, datum DATE NOT NULL, info LONGTEXT DEFAULT NULL, bedrag NUMERIC(10, 2) NOT NULL, INDEX IDX_EADEA9FFC35D3E (factuur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hs_klanten ADD CONSTRAINT FK_CC6284A1C729A47 FOREIGN KEY (geslacht_id) REFERENCES geslachten (id)');
        $this->addSql('ALTER TABLE hs_klanten ADD CONSTRAINT FK_CC6284A3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE hs_klant_memo ADD CONSTRAINT FK_177077063C427B2F FOREIGN KEY (klant_id) REFERENCES hs_klanten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_klant_memo ADD CONSTRAINT FK_17707706B4D32439 FOREIGN KEY (memo_id) REFERENCES hs_memos (id)');
        $this->addSql('ALTER TABLE hs_klant_document ADD CONSTRAINT FK_795E7D0B3C427B2F FOREIGN KEY (klant_id) REFERENCES hs_klanten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_klant_document ADD CONSTRAINT FK_795E7D0BC33F7837 FOREIGN KEY (document_id) REFERENCES hs_documenten (id)');
        $this->addSql('ALTER TABLE hs_vrijwilligers ADD CONSTRAINT FK_3FE7029676B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES vrijwilligers (id)');
        $this->addSql('ALTER TABLE hs_vrijwilligers ADD CONSTRAINT FK_3FE70296BF396750 FOREIGN KEY (id) REFERENCES hs_arbeiders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_vrijwilliger_memo ADD CONSTRAINT FK_938D702F76B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES hs_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_vrijwilliger_memo ADD CONSTRAINT FK_938D702FB4D32439 FOREIGN KEY (memo_id) REFERENCES hs_memos (id)');
        $this->addSql('ALTER TABLE hs_vrijwilliger_document ADD CONSTRAINT FK_EAEC53F376B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES hs_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_vrijwilliger_document ADD CONSTRAINT FK_EAEC53F3C33F7837 FOREIGN KEY (document_id) REFERENCES hs_documenten (id)');
        $this->addSql('ALTER TABLE hs_documenten ADD CONSTRAINT FK_87CDF0443D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE hs_declaraties ADD CONSTRAINT FK_AF23D292BA5374AF FOREIGN KEY (klus_id) REFERENCES hs_klussen (id)');
        $this->addSql('ALTER TABLE hs_declaraties ADD CONSTRAINT FK_AF23D292C35D3E FOREIGN KEY (factuur_id) REFERENCES hs_facturen (id)');
        $this->addSql('ALTER TABLE hs_declaraties ADD CONSTRAINT FK_AF23D2921EE52B26 FOREIGN KEY (declaratieCategorie_id) REFERENCES hs_declaratie_categorieen (id)');
        $this->addSql('ALTER TABLE hs_declaraties ADD CONSTRAINT FK_AF23D2923D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE hs_declaratie_document ADD CONSTRAINT FK_9E1A25FE6AE7FC36 FOREIGN KEY (declaratie_id) REFERENCES hs_declaraties (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_declaratie_document ADD CONSTRAINT FK_9E1A25FEC33F7837 FOREIGN KEY (document_id) REFERENCES hs_documenten (id)');
        $this->addSql('ALTER TABLE hs_dienstverleners ADD CONSTRAINT FK_4949548D3C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE hs_dienstverleners ADD CONSTRAINT FK_4949548DBF396750 FOREIGN KEY (id) REFERENCES hs_arbeiders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_dienstverlener_memo ADD CONSTRAINT FK_F546B7DDA1690166 FOREIGN KEY (dienstverlener_id) REFERENCES hs_dienstverleners (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_dienstverlener_memo ADD CONSTRAINT FK_F546B7DDB4D32439 FOREIGN KEY (memo_id) REFERENCES hs_memos (id)');
        $this->addSql('ALTER TABLE hs_dienstverlener_document ADD CONSTRAINT FK_DEBCC7F2A1690166 FOREIGN KEY (dienstverlener_id) REFERENCES hs_dienstverleners (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_dienstverlener_document ADD CONSTRAINT FK_DEBCC7F2C33F7837 FOREIGN KEY (document_id) REFERENCES hs_documenten (id)');
        $this->addSql('ALTER TABLE hs_facturen ADD CONSTRAINT FK_31669C163C427B2F FOREIGN KEY (klant_id) REFERENCES hs_klanten (id)');
        $this->addSql('ALTER TABLE hs_factuur_klus ADD CONSTRAINT FK_B3DD2838C35D3E FOREIGN KEY (factuur_id) REFERENCES hs_facturen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_factuur_klus ADD CONSTRAINT FK_B3DD2838BA5374AF FOREIGN KEY (klus_id) REFERENCES hs_klussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_registraties ADD CONSTRAINT FK_62041BC2BA5374AF FOREIGN KEY (klus_id) REFERENCES hs_klussen (id)');
        $this->addSql('ALTER TABLE hs_registraties ADD CONSTRAINT FK_62041BC2C35D3E FOREIGN KEY (factuur_id) REFERENCES hs_facturen (id)');
        $this->addSql('ALTER TABLE hs_registraties ADD CONSTRAINT FK_62041BC26650623E FOREIGN KEY (arbeider_id) REFERENCES hs_arbeiders (id)');
        $this->addSql('ALTER TABLE hs_registraties ADD CONSTRAINT FK_62041BC25A8A0A1 FOREIGN KEY (activiteit_id) REFERENCES hs_activiteiten (id)');
        $this->addSql('ALTER TABLE hs_registraties ADD CONSTRAINT FK_62041BC23D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE hs_memos ADD CONSTRAINT FK_4048AFA33D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE hs_klussen ADD CONSTRAINT FK_3E9A80CF3C427B2F FOREIGN KEY (klant_id) REFERENCES hs_klanten (id)');
        $this->addSql('ALTER TABLE hs_klussen ADD CONSTRAINT FK_3E9A80CF5A8A0A1 FOREIGN KEY (activiteit_id) REFERENCES hs_activiteiten (id)');
        $this->addSql('ALTER TABLE hs_klussen ADD CONSTRAINT FK_3E9A80CF3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE hs_klus_dienstverlener ADD CONSTRAINT FK_70F96EFBBA5374AF FOREIGN KEY (klus_id) REFERENCES hs_klussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_klus_dienstverlener ADD CONSTRAINT FK_70F96EFBA1690166 FOREIGN KEY (dienstverlener_id) REFERENCES hs_dienstverleners (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_klus_vrijwilliger ADD CONSTRAINT FK_6E1EDAA1BA5374AF FOREIGN KEY (klus_id) REFERENCES hs_klussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_klus_vrijwilliger ADD CONSTRAINT FK_6E1EDAA176B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES hs_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_klus_memo ADD CONSTRAINT FK_208D08ECBA5374AF FOREIGN KEY (klus_id) REFERENCES hs_klussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_klus_memo ADD CONSTRAINT FK_208D08ECB4D32439 FOREIGN KEY (memo_id) REFERENCES hs_memos (id)');
        $this->addSql('ALTER TABLE hs_betalingen ADD CONSTRAINT FK_EADEA9FFC35D3E FOREIGN KEY (factuur_id) REFERENCES hs_facturen (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
