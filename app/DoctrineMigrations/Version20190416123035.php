<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190416123035 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE scip_werkdoelen (id INT AUTO_INCREMENT NOT NULL, deelnemer_id INT DEFAULT NULL, medewerker_id INT DEFAULT NULL, tekst LONGTEXT DEFAULT NULL, datum DATE NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_6433FE805DFA57A1 (deelnemer_id), INDEX IDX_6433FE803D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scip_beschikbaarheid (id INT AUTO_INCREMENT NOT NULL, deelname_id INT DEFAULT NULL, maandagVan TIME DEFAULT NULL, maandagTot TIME DEFAULT NULL, dinsdagVan TIME DEFAULT NULL, dinsdagTot TIME DEFAULT NULL, woensdagVan TIME DEFAULT NULL, woensdagTot TIME DEFAULT NULL, donderdagVan TIME DEFAULT NULL, donderdagTot TIME DEFAULT NULL, vrijdagVan TIME DEFAULT NULL, vrijdagTot TIME DEFAULT NULL, zaterdagVan TIME DEFAULT NULL, zaterdagTot TIME DEFAULT NULL, zondagVan TIME DEFAULT NULL, zondagTot TIME DEFAULT NULL, UNIQUE INDEX UNIQ_8897F00FC18FA9D5 (deelname_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scip_verslagen (id INT AUTO_INCREMENT NOT NULL, deelnemer_id INT DEFAULT NULL, medewerker_id INT DEFAULT NULL, tekst LONGTEXT DEFAULT NULL, datum DATE NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_3AF92EB95DFA57A1 (deelnemer_id), INDEX IDX_3AF92EB93D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scip_documenten (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, filename VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, naam VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_12FFA4733D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scip_projecten (id INT AUTO_INCREMENT NOT NULL, kpl VARCHAR(255) DEFAULT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scip_deelnemers (id INT AUTO_INCREMENT NOT NULL, klant_id INT DEFAULT NULL, medewerker_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, functie VARCHAR(255) DEFAULT NULL, werkbegeleider VARCHAR(255) DEFAULT NULL, risNummer VARCHAR(255) DEFAULT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_5CB8023F3C427B2F (klant_id), UNIQUE INDEX UNIQ_5CB8023F3D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scip_deelnemer_label (deelnemer_id INT NOT NULL, label_id INT NOT NULL, INDEX IDX_2AF2CF895DFA57A1 (deelnemer_id), INDEX IDX_2AF2CF8933B92F39 (label_id), PRIMARY KEY(deelnemer_id, label_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scip_deelnemer_document (deelnemer_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_7CA418EB5DFA57A1 (deelnemer_id), UNIQUE INDEX UNIQ_7CA418EBC33F7837 (document_id), PRIMARY KEY(deelnemer_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scip_toegangsrechten (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_25CBD12E3D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scip_toegangsrecht_project (toegangsrecht_id INT NOT NULL, project_id INT NOT NULL, INDEX IDX_DA60099DAC60ED89 (toegangsrecht_id), INDEX IDX_DA60099D166D1F9C (project_id), PRIMARY KEY(toegangsrecht_id, project_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scip_deelnames (id INT AUTO_INCREMENT NOT NULL, deelnemer_id INT DEFAULT NULL, project_id INT DEFAULT NULL, active TINYINT(1) NOT NULL, INDEX IDX_FC67EC2F5DFA57A1 (deelnemer_id), INDEX IDX_FC67EC2F166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scip_labels (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE scip_werkdoelen ADD CONSTRAINT FK_6433FE805DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES scip_deelnemers (id)');
        $this->addSql('ALTER TABLE scip_werkdoelen ADD CONSTRAINT FK_6433FE803D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE scip_beschikbaarheid ADD CONSTRAINT FK_8897F00FC18FA9D5 FOREIGN KEY (deelname_id) REFERENCES scip_deelnames (id)');
        $this->addSql('ALTER TABLE scip_verslagen ADD CONSTRAINT FK_3AF92EB95DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES scip_deelnemers (id)');
        $this->addSql('ALTER TABLE scip_verslagen ADD CONSTRAINT FK_3AF92EB93D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE scip_documenten ADD CONSTRAINT FK_12FFA4733D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE scip_deelnemers ADD CONSTRAINT FK_5CB8023F3C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE scip_deelnemers ADD CONSTRAINT FK_5CB8023F3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE scip_deelnemer_label ADD CONSTRAINT FK_2AF2CF895DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES scip_deelnemers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scip_deelnemer_label ADD CONSTRAINT FK_2AF2CF8933B92F39 FOREIGN KEY (label_id) REFERENCES scip_labels (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scip_deelnemer_document ADD CONSTRAINT FK_7CA418EB5DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES scip_deelnemers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scip_deelnemer_document ADD CONSTRAINT FK_7CA418EBC33F7837 FOREIGN KEY (document_id) REFERENCES scip_documenten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scip_toegangsrechten ADD CONSTRAINT FK_25CBD12E3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE scip_toegangsrecht_project ADD CONSTRAINT FK_DA60099DAC60ED89 FOREIGN KEY (toegangsrecht_id) REFERENCES scip_toegangsrechten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scip_toegangsrecht_project ADD CONSTRAINT FK_DA60099D166D1F9C FOREIGN KEY (project_id) REFERENCES scip_projecten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scip_deelnames ADD CONSTRAINT FK_FC67EC2F5DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES scip_deelnemers (id)');
        $this->addSql('ALTER TABLE scip_deelnames ADD CONSTRAINT FK_FC67EC2F166D1F9C FOREIGN KEY (project_id) REFERENCES scip_projecten (id)');
    }

     public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
