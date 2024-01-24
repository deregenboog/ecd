<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170928105854 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE clip_clienten (id INT AUTO_INCREMENT NOT NULL, klant_id INT NOT NULL, viacategorie_id INT NOT NULL, behandelaar_id INT NOT NULL, aanmelddatum DATE NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, UNIQUE INDEX UNIQ_B7F4C67E3C427B2F (klant_id), INDEX IDX_B7F4C67EC5BB5F49 (viacategorie_id), INDEX IDX_B7F4C67E35A09212 (behandelaar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_client_document (client_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_18AEA4C519EB6921 (client_id), INDEX IDX_18AEA4C5C33F7837 (document_id), PRIMARY KEY(client_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_vragen (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, soort_id INT NOT NULL, hulpvrager_id INT NOT NULL, communicatiekanaal_id INT NOT NULL, leeftijdscategorie_id INT NOT NULL, behandelaar_id INT NOT NULL, omschrijving LONGTEXT NOT NULL, startdatum DATE NOT NULL, afsluitdatum DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_C28C591719EB6921 (client_id), INDEX IDX_C28C59173DEE50DF (soort_id), INDEX IDX_C28C591717F2E03B (hulpvrager_id), INDEX IDX_C28C591771CC83CE (communicatiekanaal_id), INDEX IDX_C28C59172EC18014 (leeftijdscategorie_id), INDEX IDX_C28C591735A09212 (behandelaar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_vraag_document (vraag_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_37F7BFD72CE1D7E6 (vraag_id), INDEX IDX_37F7BFD7C33F7837 (document_id), PRIMARY KEY(vraag_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_contactmomenten (id INT AUTO_INCREMENT NOT NULL, vraag_id INT NOT NULL, behandelaar_id INT NOT NULL, datum DATE NOT NULL, opmerking LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_8C4DFF3D2CE1D7E6 (vraag_id), INDEX IDX_8C4DFF3D35A09212 (behandelaar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_viacategorieen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_hulpvragersoorten (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_documenten (id INT AUTO_INCREMENT NOT NULL, behandelaar_id INT NOT NULL, naam VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_98FCA35A09212 (behandelaar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_communicatiekanalen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_leeftijdscategorieen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_vraagsoorten (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clip_behandelaars (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT DEFAULT NULL, naam VARCHAR(255) DEFAULT NULL, display_name VARCHAR(255) DEFAULT NULL, active TINYINT(1) NOT NULL, INDEX IDX_4B016D223D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clip_clienten ADD CONSTRAINT FK_B7F4C67E3C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE clip_clienten ADD CONSTRAINT FK_B7F4C67EC5BB5F49 FOREIGN KEY (viacategorie_id) REFERENCES clip_viacategorieen (id)');
        $this->addSql('ALTER TABLE clip_clienten ADD CONSTRAINT FK_B7F4C67E35A09212 FOREIGN KEY (behandelaar_id) REFERENCES clip_behandelaars (id)');
        $this->addSql('ALTER TABLE clip_client_document ADD CONSTRAINT FK_18AEA4C519EB6921 FOREIGN KEY (client_id) REFERENCES clip_clienten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clip_client_document ADD CONSTRAINT FK_18AEA4C5C33F7837 FOREIGN KEY (document_id) REFERENCES clip_documenten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clip_vragen ADD CONSTRAINT FK_C28C591719EB6921 FOREIGN KEY (client_id) REFERENCES clip_clienten (id)');
        $this->addSql('ALTER TABLE clip_vragen ADD CONSTRAINT FK_C28C59173DEE50DF FOREIGN KEY (soort_id) REFERENCES clip_vraagsoorten (id)');
        $this->addSql('ALTER TABLE clip_vragen ADD CONSTRAINT FK_C28C591717F2E03B FOREIGN KEY (hulpvrager_id) REFERENCES clip_hulpvragersoorten (id)');
        $this->addSql('ALTER TABLE clip_vragen ADD CONSTRAINT FK_C28C591771CC83CE FOREIGN KEY (communicatiekanaal_id) REFERENCES clip_communicatiekanalen (id)');
        $this->addSql('ALTER TABLE clip_vragen ADD CONSTRAINT FK_C28C59172EC18014 FOREIGN KEY (leeftijdscategorie_id) REFERENCES clip_leeftijdscategorieen (id)');
        $this->addSql('ALTER TABLE clip_vragen ADD CONSTRAINT FK_C28C591735A09212 FOREIGN KEY (behandelaar_id) REFERENCES clip_behandelaars (id)');
        $this->addSql('ALTER TABLE clip_vraag_document ADD CONSTRAINT FK_37F7BFD72CE1D7E6 FOREIGN KEY (vraag_id) REFERENCES clip_vragen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clip_vraag_document ADD CONSTRAINT FK_37F7BFD7C33F7837 FOREIGN KEY (document_id) REFERENCES clip_documenten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clip_contactmomenten ADD CONSTRAINT FK_8C4DFF3D2CE1D7E6 FOREIGN KEY (vraag_id) REFERENCES clip_vragen (id)');
        $this->addSql('ALTER TABLE clip_contactmomenten ADD CONSTRAINT FK_8C4DFF3D35A09212 FOREIGN KEY (behandelaar_id) REFERENCES clip_behandelaars (id)');
        $this->addSql('ALTER TABLE clip_documenten ADD CONSTRAINT FK_98FCA35A09212 FOREIGN KEY (behandelaar_id) REFERENCES clip_behandelaars (id)');
        $this->addSql('ALTER TABLE clip_behandelaars ADD CONSTRAINT FK_4B016D223D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
