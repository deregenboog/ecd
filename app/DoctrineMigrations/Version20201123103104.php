<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123103104 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('CREATE TABLE odp_huurders_odp_projecten (odp_huurder_id INT NOT NULL, odp_project_id INT NOT NULL, INDEX IDX_48E405357776076A (odp_huurder_id), INDEX IDX_48E40535FF532D2C (odp_project_id), PRIMARY KEY(odp_huurder_id, odp_project_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_huurverzoeken_odp_projecten (odp_huurverzoek_id INT NOT NULL, odp_project_id INT NOT NULL, INDEX IDX_CDF6EEBCE7540572 (odp_huurverzoek_id), INDEX IDX_CDF6EEBCFF532D2C (odp_project_id), PRIMARY KEY(odp_huurverzoek_id, odp_project_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odp_projecten (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, startdatum DATE NOT NULL, einddatum DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE odp_huurders_odp_projecten ADD CONSTRAINT FK_48E405357776076A FOREIGN KEY (odp_huurder_id) REFERENCES odp_deelnemers (id)');
        $this->addSql('ALTER TABLE odp_huurders_odp_projecten ADD CONSTRAINT FK_48E40535FF532D2C FOREIGN KEY (odp_project_id) REFERENCES odp_projecten (id)');
        $this->addSql('ALTER TABLE odp_huurverzoeken_odp_projecten ADD CONSTRAINT FK_CDF6EEBCE7540572 FOREIGN KEY (odp_huurverzoek_id) REFERENCES odp_huurverzoeken (id)');
        $this->addSql('ALTER TABLE odp_huurverzoeken_odp_projecten ADD CONSTRAINT FK_CDF6EEBCFF532D2C FOREIGN KEY (odp_project_id) REFERENCES odp_projecten (id)');

        ;
        $this->addSql('ALTER TABLE odp_deelnemers ADD project_id INT NULL DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_deelnemers ADD CONSTRAINT FK_20283999166D1F9C FOREIGN KEY (project_id) REFERENCES odp_projecten (id)');
        $this->addSql('CREATE INDEX IDX_20283999166D1F9C ON odp_deelnemers (project_id)');

        $this->addSql('ALTER TABLE odp_huuraanbiedingen ADD project_id INT');
        $this->addSql('ALTER TABLE odp_huuraanbiedingen ADD CONSTRAINT FK_FA204F87166D1F9C FOREIGN KEY (project_id) REFERENCES odp_projecten (id)');
        $this->addSql('CREATE INDEX IDX_FA204F87166D1F9C ON odp_huuraanbiedingen (project_id)');
        $this->addSql("INSERT INTO `odp_projecten` (`id`, `naam`, `startdatum`, `einddatum`) VALUES (1, 'Onder de pannen', '2020-9-01', NULL)");
        $this->addSql("INSERT INTO `odp_projecten` (`id`, `naam`, `startdatum`, `einddatum`) VALUES (2, 'Parentshouse', '2020-9-01', NULL)");
        $this->addSql("INSERT INTO `odp_projecten` (`id`, `naam`, `startdatum`, `einddatum`) VALUES (3, 'ABRI', '2020-9-01', NULL)");


        $this->addSql("UPDATE odp_huuraanbiedingen AS ha
INNER JOIN odp_deelnemers AS d ON d.id = ha.verhuurder_id
INNER JOIN klanten AS k ON k.id = d.klant_id
SET ha.project_id = 3
WHERE k.voornaam LIKE '%Time%'");

        $this->addSql("UPDATE odp_huuraanbiedingen AS ha
INNER JOIN odp_deelnemers AS d ON d.id = ha.verhuurder_id
INNER JOIN klanten AS k ON k.id = d.klant_id
SET ha.project_id = 3
WHERE k.voornaam LIKE 'AHAM%'");

        $this->addSql("UPDATE odp_huuraanbiedingen AS ha
INNER JOIN odp_deelnemers AS d ON d.id = ha.verhuurder_id
INNER JOIN klanten AS k ON k.id = d.klant_id
SET ha.project_id = 2
WHERE k.achternaam LIKE 'Parentshouse%'");

        $this->addSql("UPDATE odp_huuraanbiedingen AS ha
INNER JOIN odp_deelnemers AS d ON d.id = ha.verhuurder_id
INNER JOIN klanten AS k ON k.id = d.klant_id
SET ha.project_id = 1
WHERE ha.project_id IS NULL");

        $this->addSql("INSERT IGNORE INTO odp_huurverzoeken_odp_projecten (odp_huurverzoek_id, odp_project_id)
SELECT DISTINCT hv.id, ha.project_id FROM odp_huurverzoeken AS hv 
INNER JOIN odp_huurovereenkomsten AS ho ON ho.huurverzoek_id = hv.id
INNER JOIN odp_huuraanbiedingen AS ha ON ho.huuraanbod_id = ha.id
GROUP BY hv.id, ha.project_id");

        $this->addSql("INSERT IGNORE INTO odp_huurders_odp_projecten (odp_huurder_id, odp_project_id)
SELECT hv.huurder_id, hvp.odp_project_id FROM odp_huurverzoeken_odp_projecten AS hvp
INNER JOIN odp_huurverzoeken AS hv ON hv.id = hvp.odp_huurverzoek_id");

        $this->addSql("INSERT IGNORE INTO odp_huurders_odp_projecten (odp_huurder_id, odp_project_id)
SELECT h.id,1 FROM odp_deelnemers AS h 
LEFT JOIN odp_huurders_odp_projecten AS hp ON hp.odp_huurder_id = h.id
WHERE hp.odp_huurder_id IS NULL AND h.model = 'Klant'");

        $this->addSql("UPDATE odp_deelnemers AS d 
        INNER JOIN odp_huuraanbiedingen AS ha ON d.id = ha.verhuurder_id 
        SET d.project_id = ha.project_id WHERE d.model = 'Verhuurder'");
    }

     public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE odp_huurders_odp_projecten');
        $this->addSql('DROP TABLE odp_huurverzoeken_odp_projecten');
        $this->addSql('DROP TABLE odp_projecten');


        $this->addSql('ALTER TABLE odp_deelnemers DROP project_id');
        $this->addSql('ALTER TABLE odp_huuraanbiedingen DROP project_id');


    }
}
