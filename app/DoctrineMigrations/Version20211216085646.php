<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211216085646 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('CREATE TABLE tw_aanvullinginkomen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL DEFAULT NOW(), modified DATETIME NOT NULL DEFAULT NOW(), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tw_kwijtschelding (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL DEFAULT NOW(), modified DATETIME NOT NULL DEFAULT NOW(), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql("INSERT INTO tw_aanvullinginkomen (naam, `active`) VALUES ('Ja',1), ('Via gemeente',1),('Ja, via UWV',1),('Nee',1),('Onbekend',1)");
        $this->addSql("INSERT INTO tw_kwijtschelding (naam, `active`) VALUES ('Ja',1),('Nee',1),('Onbekend',1)");

        $this->addSql('ALTER TABLE tw_deelnemers ADD kwijtschelding_id INT DEFAULT NULL, ADD aanvullingInkomen_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E431725650B81711 FOREIGN KEY (aanvullingInkomen_id) REFERENCES tw_aanvullinginkomen (id)');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E4317256BD67529F FOREIGN KEY (kwijtschelding_id) REFERENCES tw_kwijtschelding (id)');
        $this->addSql('CREATE INDEX IDX_E431725650B81711 ON tw_deelnemers (aanvullingInkomen_id)');
        $this->addSql('CREATE INDEX IDX_E4317256BD67529F ON tw_deelnemers (kwijtschelding_id)');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E431725650B81711');
        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E4317256BD67529F');

        $this->addSql('DROP TABLE tw_aanvullinginkomen');
        $this->addSql('DROP TABLE tw_kwijtschelding');


        $this->addSql('DROP INDEX IDX_E431725650B81711 ON tw_deelnemers');
        $this->addSql('DROP INDEX IDX_E4317256BD67529F ON tw_deelnemers');
        $this->addSql('ALTER TABLE tw_deelnemers DROP kwijtschelding_id, DROP aanvullingInkomen_id');

    }
}
