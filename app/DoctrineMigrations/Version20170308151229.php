<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170308151229 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE odp_deelnemers (id INT AUTO_INCREMENT NOT NULL, foreign_key INT NOT NULL, medewerker_id INT NOT NULL, woningbouwcorporatie_id INT DEFAULT NULL, aanmelddatum DATE NOT NULL, afsluitdatum DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, model VARCHAR(255) NOT NULL, afsluiting_id INT DEFAULT NULL, woningbouwcorporatie_toelichting VARCHAR(255) DEFAULT NULL, INDEX IDX_202839997E366551 (foreign_key), INDEX IDX_202839993D707F64 (medewerker_id), INDEX IDX_20283999ECDAD1A9 (afsluiting_id), INDEX IDX_20283999C0B11400 (woningbouwcorporatie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_deelnemer_verslag (deelnemer_id INT NOT NULL, verslag_id INT NOT NULL, INDEX IDX_F8F75D6A5DFA57A1 (deelnemer_id), INDEX IDX_F8F75D6AD949475D (verslag_id), PRIMARY KEY(deelnemer_id, verslag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_deelnemer_document (deelnemer_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_9BA61CC55DFA57A1 (deelnemer_id), INDEX IDX_9BA61CC5C33F7837 (document_id), PRIMARY KEY(deelnemer_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_huurverzoeken (id INT AUTO_INCREMENT NOT NULL, huurder_id INT DEFAULT NULL, medewerker_id INT NOT NULL, startdatum DATE NOT NULL, einddatum DATE DEFAULT NULL, actief TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_588F4E969E4835DA (huurder_id), INDEX IDX_588F4E963D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_huurverzoek_verslag (huurverzoek_id INT NOT NULL, verslag_id INT NOT NULL, INDEX IDX_46CB48C145DA3BB7 (huurverzoek_id), INDEX IDX_46CB48C1D949475D (verslag_id), PRIMARY KEY(huurverzoek_id, verslag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_huurovereenkomst_afsluitingen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_intakes (id INT AUTO_INCREMENT NOT NULL, deelnemer_id INT DEFAULT NULL, medewerker_id INT NOT NULL, intake_datum DATE NOT NULL, gezin_met_kinderen TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, UNIQUE INDEX UNIQ_3A1E7F775DFA57A1 (deelnemer_id), INDEX IDX_3A1E7F773D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_documenten (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, naam VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_6E6F9FD53D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_coordinatoren (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, UNIQUE INDEX UNIQ_62BCCDB53D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_verslagen (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, datum DATETIME NOT NULL, opmerking LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_762D3F773D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_huurder_afsluitingen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_woningbouwcorporaties (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_huuraanbiedingen (id INT AUTO_INCREMENT NOT NULL, verhuurder_id INT DEFAULT NULL, medewerker_id INT NOT NULL, startdatum DATE NOT NULL, einddatum DATE DEFAULT NULL, actief TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_FA204F877E18485D (verhuurder_id), INDEX IDX_FA204F873D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_huuraanbod_verslag (huuraanbod_id INT NOT NULL, verslag_id INT NOT NULL, INDEX IDX_9B2DE75B656E2280 (huuraanbod_id), INDEX IDX_9B2DE75BD949475D (verslag_id), PRIMARY KEY(huuraanbod_id, verslag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_verhuurder_afsluiting (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_huurovereenkomsten (id INT AUTO_INCREMENT NOT NULL, huuraanbod_id INT DEFAULT NULL, huurverzoek_id INT DEFAULT NULL, medewerker_id INT NOT NULL, startdatum DATE NOT NULL, opzegdatum DATE DEFAULT NULL, einddatum DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, huurovereenkomstAfsluiting_id INT DEFAULT NULL, INDEX IDX_453FF4A6656E2280 (huuraanbod_id), INDEX IDX_453FF4A645DA3BB7 (huurverzoek_id), INDEX IDX_453FF4A660850352 (huurovereenkomstAfsluiting_id), INDEX IDX_453FF4A63D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_huurovereenkomst_verslag (huurovereenkomst_id INT NOT NULL, verslag_id INT NOT NULL, INDEX IDX_114A2160870B85BC (huurovereenkomst_id), INDEX IDX_114A2160D949475D (verslag_id), PRIMARY KEY(huurovereenkomst_id, verslag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_huurovereenkomst_document (huurovereenkomst_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_7B9A48A7870B85BC (huurovereenkomst_id), INDEX IDX_7B9A48A7C33F7837 (document_id), PRIMARY KEY(huurovereenkomst_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE odp_deelnemers ADD CONSTRAINT FK_202839997E366551 FOREIGN KEY (foreign_key) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE odp_deelnemers ADD CONSTRAINT FK_202839993D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE odp_deelnemers ADD CONSTRAINT FK_20283999C0B11400 FOREIGN KEY (woningbouwcorporatie_id) REFERENCES odp_woningbouwcorporaties (id)');
        $this->addSql('ALTER TABLE odp_deelnemer_verslag ADD CONSTRAINT FK_F8F75D6A5DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES odp_deelnemers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odp_deelnemer_verslag ADD CONSTRAINT FK_F8F75D6AD949475D FOREIGN KEY (verslag_id) REFERENCES odp_verslagen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odp_deelnemer_document ADD CONSTRAINT FK_9BA61CC55DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES odp_deelnemers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odp_deelnemer_document ADD CONSTRAINT FK_9BA61CC5C33F7837 FOREIGN KEY (document_id) REFERENCES odp_documenten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odp_huurverzoeken ADD CONSTRAINT FK_588F4E969E4835DA FOREIGN KEY (huurder_id) REFERENCES odp_deelnemers (id)');
        $this->addSql('ALTER TABLE odp_huurverzoeken ADD CONSTRAINT FK_588F4E963D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE odp_huurverzoek_verslag ADD CONSTRAINT FK_46CB48C145DA3BB7 FOREIGN KEY (huurverzoek_id) REFERENCES odp_huurverzoeken (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odp_huurverzoek_verslag ADD CONSTRAINT FK_46CB48C1D949475D FOREIGN KEY (verslag_id) REFERENCES odp_verslagen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odp_intakes ADD CONSTRAINT FK_3A1E7F775DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES odp_deelnemers (id)');
        $this->addSql('ALTER TABLE odp_intakes ADD CONSTRAINT FK_3A1E7F773D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE odp_documenten ADD CONSTRAINT FK_6E6F9FD53D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE odp_coordinatoren ADD CONSTRAINT FK_62BCCDB53D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE odp_verslagen ADD CONSTRAINT FK_762D3F773D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE odp_huuraanbiedingen ADD CONSTRAINT FK_FA204F877E18485D FOREIGN KEY (verhuurder_id) REFERENCES odp_deelnemers (id)');
        $this->addSql('ALTER TABLE odp_huuraanbiedingen ADD CONSTRAINT FK_FA204F873D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE odp_huuraanbod_verslag ADD CONSTRAINT FK_9B2DE75B656E2280 FOREIGN KEY (huuraanbod_id) REFERENCES odp_huuraanbiedingen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odp_huuraanbod_verslag ADD CONSTRAINT FK_9B2DE75BD949475D FOREIGN KEY (verslag_id) REFERENCES odp_verslagen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_453FF4A6656E2280 FOREIGN KEY (huuraanbod_id) REFERENCES odp_huuraanbiedingen (id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_453FF4A645DA3BB7 FOREIGN KEY (huurverzoek_id) REFERENCES odp_huurverzoeken (id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_453FF4A660850352 FOREIGN KEY (huurovereenkomstAfsluiting_id) REFERENCES odp_huurovereenkomst_afsluitingen (id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD CONSTRAINT FK_453FF4A63D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE odp_huurovereenkomst_verslag ADD CONSTRAINT FK_114A2160870B85BC FOREIGN KEY (huurovereenkomst_id) REFERENCES odp_huurovereenkomsten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odp_huurovereenkomst_verslag ADD CONSTRAINT FK_114A2160D949475D FOREIGN KEY (verslag_id) REFERENCES odp_verslagen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odp_huurovereenkomst_document ADD CONSTRAINT FK_7B9A48A7870B85BC FOREIGN KEY (huurovereenkomst_id) REFERENCES odp_huurovereenkomsten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odp_huurovereenkomst_document ADD CONSTRAINT FK_7B9A48A7C33F7837 FOREIGN KEY (document_id) REFERENCES odp_documenten (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
     public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
