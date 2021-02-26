<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210226112642 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE mw_dossier_statussen ADD binnenViaOptieKlant_id INT NOT NULL DEFAULT 0');
        $this->addSql('INSERT INTO `mw_binnen_via` (`id`, `naam`, `active`, `created`, `modified`, `class`) VALUES (0, "Onbekend", 1, CURRENT_DATE(), CURRENT_DATE(), "BinnenViaOptieKlant")');
        $this->addSql('
INSERT INTO `mw_binnen_via` (`naam`, `active`, `created`, `modified`, `class`) VALUES ("Madi/buurtteams", 1, CURRENT_DATE(), CURRENT_DATE(), "BinnenViaOptieKlant");
INSERT INTO `mw_binnen_via` (`naam`, `active`, `created`, `modified`, `class`) VALUES ("DRG maatschappelijk werk", 1, CURRENT_DATE(), CURRENT_DATE(), "BinnenViaOptieKlant");
INSERT INTO `mw_binnen_via` (`naam`, `active`, `created`, `modified`, `class`) VALUES ("GGD", 1, CURRENT_DATE(), CURRENT_DATE(), "BinnenViaOptieKlant");
INSERT INTO `mw_binnen_via` (`naam`, `active`, `created`, `modified`, `class`) VALUES ("WPI", 1, CURRENT_DATE(), CURRENT_DATE(), "BinnenViaOptieKlant");
INSERT INTO `mw_binnen_via` (`naam`, `active`, `created`, `modified`, `class`) VALUES ("Gemeente", 1, CURRENT_DATE(), CURRENT_DATE(), "BinnenViaOptieKlant");
INSERT INTO `mw_binnen_via` (`naam`, `active`, `created`, `modified`, `class`) VALUES ("Zelfmelders", 1, CURRENT_DATE(), CURRENT_DATE(), "BinnenViaOptieKlant");
INSERT INTO `mw_binnen_via` (`naam`, `active`, `created`, `modified`, `class`) VALUES ("Straatjurist/Ombudsvrouw", 1, CURRENT_DATE(), CURRENT_DATE(), "BinnenViaOptieKlant");
INSERT INTO `mw_binnen_via` (`naam`, `active`, `created`, `modified`, `class`) VALUES ("LVB instellingen", 1, CURRENT_DATE(), CURRENT_DATE(), "BinnenViaOptieKlant");
INSERT INTO `mw_binnen_via` (`naam`, `active`, `created`, `modified`, `class`) VALUES ("Inloophuizen", 1, CURRENT_DATE(), CURRENT_DATE(), "BinnenViaOptieKlant");
INSERT INTO `mw_binnen_via` (`naam`, `active`, `created`, `modified`, `class`) VALUES ("Huis van de Wijk", 1, CURRENT_DATE(), CURRENT_DATE(), "BinnenViaOptieKlant");
INSERT INTO `mw_binnen_via` (`naam`, `active`, `created`, `modified`, `class`) VALUES ("Tijdelijk Wonen DRG", 1, CURRENT_DATE(), CURRENT_DATE(), "BinnenViaOptieKlant");
INSERT INTO `mw_binnen_via` (`naam`, `active`, `created`, `modified`, `class`) VALUES ("Woningbouwcorporaties", 1, CURRENT_DATE(), CURRENT_DATE(), "BinnenViaOptieKlant");
INSERT INTO `mw_binnen_via` (`naam`, `active`, `created`, `modified`, `class`) VALUES ("Overig", 1, CURRENT_DATE(), CURRENT_DATE(), "BinnenViaOptieKlant");
');
        $this->addSql("UPDATE `mw_binnen_via` SET `id` = '0' WHERE `mw_binnen_via`.`naam` = 'Onbekend'");
        $this->addSql('ALTER TABLE mw_dossier_statussen ADD CONSTRAINT FK_D74783BB305E4E53 FOREIGN KEY (binnenViaOptieKlant_id) REFERENCES mw_binnen_via (id)');
        $this->addSql('CREATE INDEX IDX_D74783BB305E4E53 ON mw_dossier_statussen (binnenViaOptieKlant_id)');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mw_dossier_statussen DROP FOREIGN KEY FK_D74783BB305E4E53');
        $this->addSql('DROP INDEX IDX_D74783BB305E4E53 ON mw_dossier_statussen');
        $this->addSql('ALTER TABLE mw_dossier_statussen DROP binnenViaOptieKlant_id');
        $this->addSql('DELETE FROM mw_binnen_via WHERE id = 0');


    }
}
