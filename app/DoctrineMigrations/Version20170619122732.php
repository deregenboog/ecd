<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170619122732 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dagbesteding_afsluitingen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_dagdelen (id INT AUTO_INCREMENT NOT NULL, traject_id INT NOT NULL, project_id INT NOT NULL, datum DATE NOT NULL, dagdeel VARCHAR(255) NOT NULL, INDEX IDX_54F41972A0CADD4 (traject_id), INDEX IDX_54F41972166D1F9C (project_id), UNIQUE INDEX unique_traject_datum_dagdeel_idx (traject_id, datum, dagdeel), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_resultaatgebieden (id INT AUTO_INCREMENT NOT NULL, traject_id INT DEFAULT NULL, soort_id INT DEFAULT NULL, startdatum DATE NOT NULL, INDEX IDX_4F7529D3A0CADD4 (traject_id), INDEX IDX_4F7529D33DEE50DF (soort_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_rapportages (id INT AUTO_INCREMENT NOT NULL, traject_id INT DEFAULT NULL, medewerker_id INT DEFAULT NULL, datum DATE NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_FBA61484A0CADD4 (traject_id), INDEX IDX_FBA614843D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_rapportage_document (rapportage_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_8ED5B83668A3850 (rapportage_id), INDEX IDX_8ED5B83C33F7837 (document_id), PRIMARY KEY(rapportage_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_resultaatgebiedsoorten (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_verslagen (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, datum DATETIME NOT NULL, opmerking LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_F41415953D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_contactpersonen (id INT AUTO_INCREMENT NOT NULL, deelnemer_id INT DEFAULT NULL, soort VARCHAR(255) NOT NULL, naam VARCHAR(255) NOT NULL, telefoon VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, opmerking LONGTEXT DEFAULT NULL, INDEX IDX_C14C44B85DFA57A1 (deelnemer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_documenten (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, naam VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_20E925AB3D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_projecten (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_deelnemers (id INT AUTO_INCREMENT NOT NULL, afsluiting_id INT DEFAULT NULL, klant_id INT NOT NULL, medewerker_id INT NOT NULL, risDossiernummer VARCHAR(255) DEFAULT NULL, aanmelddatum DATE NOT NULL, afsluitdatum DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_6EAE83E7ECDAD1A9 (afsluiting_id), UNIQUE INDEX UNIQ_6EAE83E73C427B2F (klant_id), INDEX IDX_6EAE83E73D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_deelnemer_verslag (deelnemer_id INT NOT NULL, verslag_id INT NOT NULL, INDEX IDX_BA9CAC335DFA57A1 (deelnemer_id), INDEX IDX_BA9CAC33D949475D (verslag_id), PRIMARY KEY(deelnemer_id, verslag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_deelnemer_document (deelnemer_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_89539E645DFA57A1 (deelnemer_id), INDEX IDX_89539E64C33F7837 (document_id), PRIMARY KEY(deelnemer_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_trajectsoorten (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_trajecten (id INT AUTO_INCREMENT NOT NULL, deelnemer_id INT NOT NULL, soort_id INT NOT NULL, resultaatgebied_id INT DEFAULT NULL, begeleider_id INT NOT NULL, afsluiting_id INT DEFAULT NULL, startdatum DATE NOT NULL, einddatum DATE NOT NULL, afsluitdatum DATE DEFAULT NULL, INDEX IDX_670A67F25DFA57A1 (deelnemer_id), INDEX IDX_670A67F23DEE50DF (soort_id), UNIQUE INDEX UNIQ_670A67F21BE6904 (resultaatgebied_id), INDEX IDX_670A67F239F86A1D (begeleider_id), INDEX IDX_670A67F2ECDAD1A9 (afsluiting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_traject_verslag (traject_id INT NOT NULL, verslag_id INT NOT NULL, INDEX IDX_ECCFAC5CA0CADD4 (traject_id), INDEX IDX_ECCFAC5CD949475D (verslag_id), PRIMARY KEY(traject_id, verslag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_traject_document (traject_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_5408B1ADA0CADD4 (traject_id), INDEX IDX_5408B1ADC33F7837 (document_id), PRIMARY KEY(traject_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_traject_locatie (traject_id INT NOT NULL, locatie_id INT NOT NULL, INDEX IDX_1D887470A0CADD4 (traject_id), INDEX IDX_1D8874704947630C (locatie_id), PRIMARY KEY(traject_id, locatie_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_traject_project (traject_id INT NOT NULL, project_id INT NOT NULL, INDEX IDX_9DF4F8B0A0CADD4 (traject_id), INDEX IDX_9DF4F8B0166D1F9C (project_id), PRIMARY KEY(traject_id, project_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_trajectbegeleiders (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, UNIQUE INDEX UNIQ_EA2465533D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dagbesteding_locaties (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dagbesteding_dagdelen ADD CONSTRAINT FK_54F41972A0CADD4 FOREIGN KEY (traject_id) REFERENCES dagbesteding_trajecten (id)');
        $this->addSql('ALTER TABLE dagbesteding_dagdelen ADD CONSTRAINT FK_54F41972166D1F9C FOREIGN KEY (project_id) REFERENCES dagbesteding_projecten (id)');
        $this->addSql('ALTER TABLE dagbesteding_resultaatgebieden ADD CONSTRAINT FK_4F7529D3A0CADD4 FOREIGN KEY (traject_id) REFERENCES dagbesteding_trajecten (id)');
        $this->addSql('ALTER TABLE dagbesteding_resultaatgebieden ADD CONSTRAINT FK_4F7529D33DEE50DF FOREIGN KEY (soort_id) REFERENCES dagbesteding_resultaatgebiedsoorten (id)');
        $this->addSql('ALTER TABLE dagbesteding_rapportages ADD CONSTRAINT FK_FBA61484A0CADD4 FOREIGN KEY (traject_id) REFERENCES dagbesteding_trajecten (id)');
        $this->addSql('ALTER TABLE dagbesteding_rapportages ADD CONSTRAINT FK_FBA614843D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE dagbesteding_rapportage_document ADD CONSTRAINT FK_8ED5B83668A3850 FOREIGN KEY (rapportage_id) REFERENCES dagbesteding_rapportages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_rapportage_document ADD CONSTRAINT FK_8ED5B83C33F7837 FOREIGN KEY (document_id) REFERENCES dagbesteding_documenten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_verslagen ADD CONSTRAINT FK_F41415953D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE dagbesteding_contactpersonen ADD CONSTRAINT FK_C14C44B85DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES dagbesteding_deelnemers (id)');
        $this->addSql('ALTER TABLE dagbesteding_documenten ADD CONSTRAINT FK_20E925AB3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE dagbesteding_deelnemers ADD CONSTRAINT FK_6EAE83E7ECDAD1A9 FOREIGN KEY (afsluiting_id) REFERENCES dagbesteding_afsluitingen (id)');
        $this->addSql('ALTER TABLE dagbesteding_deelnemers ADD CONSTRAINT FK_6EAE83E73C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE dagbesteding_deelnemers ADD CONSTRAINT FK_6EAE83E73D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE dagbesteding_deelnemer_verslag ADD CONSTRAINT FK_BA9CAC335DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES dagbesteding_deelnemers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_deelnemer_verslag ADD CONSTRAINT FK_BA9CAC33D949475D FOREIGN KEY (verslag_id) REFERENCES dagbesteding_verslagen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_deelnemer_document ADD CONSTRAINT FK_89539E645DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES dagbesteding_deelnemers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_deelnemer_document ADD CONSTRAINT FK_89539E64C33F7837 FOREIGN KEY (document_id) REFERENCES dagbesteding_documenten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_trajecten ADD CONSTRAINT FK_670A67F25DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES dagbesteding_deelnemers (id)');
        $this->addSql('ALTER TABLE dagbesteding_trajecten ADD CONSTRAINT FK_670A67F23DEE50DF FOREIGN KEY (soort_id) REFERENCES dagbesteding_trajectsoorten (id)');
        $this->addSql('ALTER TABLE dagbesteding_trajecten ADD CONSTRAINT FK_670A67F21BE6904 FOREIGN KEY (resultaatgebied_id) REFERENCES dagbesteding_resultaatgebieden (id)');
        $this->addSql('ALTER TABLE dagbesteding_trajecten ADD CONSTRAINT FK_670A67F239F86A1D FOREIGN KEY (begeleider_id) REFERENCES dagbesteding_trajectbegeleiders (id)');
        $this->addSql('ALTER TABLE dagbesteding_trajecten ADD CONSTRAINT FK_670A67F2ECDAD1A9 FOREIGN KEY (afsluiting_id) REFERENCES dagbesteding_afsluitingen (id)');
        $this->addSql('ALTER TABLE dagbesteding_traject_verslag ADD CONSTRAINT FK_ECCFAC5CA0CADD4 FOREIGN KEY (traject_id) REFERENCES dagbesteding_trajecten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_traject_verslag ADD CONSTRAINT FK_ECCFAC5CD949475D FOREIGN KEY (verslag_id) REFERENCES dagbesteding_verslagen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_traject_document ADD CONSTRAINT FK_5408B1ADA0CADD4 FOREIGN KEY (traject_id) REFERENCES dagbesteding_trajecten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_traject_document ADD CONSTRAINT FK_5408B1ADC33F7837 FOREIGN KEY (document_id) REFERENCES dagbesteding_documenten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_traject_locatie ADD CONSTRAINT FK_1D887470A0CADD4 FOREIGN KEY (traject_id) REFERENCES dagbesteding_trajecten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_traject_locatie ADD CONSTRAINT FK_1D8874704947630C FOREIGN KEY (locatie_id) REFERENCES dagbesteding_locaties (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_traject_project ADD CONSTRAINT FK_9DF4F8B0A0CADD4 FOREIGN KEY (traject_id) REFERENCES dagbesteding_trajecten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_traject_project ADD CONSTRAINT FK_9DF4F8B0166D1F9C FOREIGN KEY (project_id) REFERENCES dagbesteding_projecten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_trajectbegeleiders ADD CONSTRAINT FK_EA2465533D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
    }

    /**
     * @param Schema $schema
     */
     public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
