<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200713105157 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mw_dossier_statussen (id INT AUTO_INCREMENT NOT NULL, klant_id INT NOT NULL, medewerker_id INT DEFAULT NULL, reden_id INT DEFAULT NULL, land_id INT DEFAULT NULL, datum DATE NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, class VARCHAR(255) NOT NULL, toelichting LONGTEXT DEFAULT NULL, INDEX IDX_D74783BB3C427B2F (klant_id), INDEX IDX_D74783BB3D707F64 (medewerker_id), INDEX IDX_D74783BBD29703A5 (reden_id), INDEX IDX_D74783BB1994904A (land_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_afsluiting_redenen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, actief TINYINT(1) NOT NULL, gewicht INT NOT NULL, land TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO mw_afsluiting_redenen (naam,actief,gewicht,land,created,modified) VALUES  ("Begeleiding afgerond",1,0,0,NOW(),NOW()),
                            ("Begeleiding overgedragen",1,0,0,NOW(),NOW()),
                            ("Client langer dan 1 jaar niet geweest",1,0,0,NOW(),NOW()),
                            ("Client gerepatrieerd",1,0,1,NOW(),NOW()),
                            ("Client overleden",1,0,0,NOW(),NOW()),
                            ("Overig",1,0,0,NOW(),NOW() )
                        ');


        $this->addSql('ALTER TABLE mw_dossier_statussen ADD CONSTRAINT FK_D74783BB3C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE mw_dossier_statussen ADD CONSTRAINT FK_D74783BB3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE mw_dossier_statussen ADD CONSTRAINT FK_D74783BBD29703A5 FOREIGN KEY (reden_id) REFERENCES mw_afsluiting_redenen (id)');
        $this->addSql('ALTER TABLE mw_dossier_statussen ADD CONSTRAINT FK_D74783BB1994904A FOREIGN KEY (land_id) REFERENCES landen (id)');

       $this->addSql('ALTER TABLE klanten ADD UNIQUE INDEX UNIQ_F538C5BC8B2671BD (huidigeStatus_id,id)');

        $this->addSql('ALTER TABLE klanten ADD huidigeMwStatus_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE klanten ADD CONSTRAINT FK_F538C5BCCC5FC3F9 FOREIGN KEY (huidigeMwStatus_id) REFERENCES mw_dossier_statussen (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F538C5BCCC5FC3F9 ON klanten (huidigeMwStatus_id)');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE klanten DROP FOREIGN KEY FK_F538C5BCCC5FC3F9');
        $this->addSql('ALTER TABLE mw_dossier_statussen DROP FOREIGN KEY FK_D74783BBD29703A5');

        $this->addSql('DROP TABLE mw_dossier_statussen');
        $this->addSql('DROP TABLE mw_afsluiting_redenen');


        $this->addSql('DROP INDEX UNIQ_F538C5BCCC5FC3F9 ON klanten');

        $this->addSql('ALTER TABLE klanten DROP huidigeMwStatus_id');


    }
}
