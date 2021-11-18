<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211118140638 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tw_inkomen ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW(), CHANGE active active TINYINT(1) NOT NULL DEFAULT \'1\', CHANGE label naam VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tw_huisdieren ADD active TINYINT(1) NOT NULL, ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW(), CHANGE label naam VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tw_traplopen ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW(), CHANGE active active TINYINT(1) NOT NULL DEFAULT \'1\', CHANGE label naam VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tw_moscreening ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW(), CHANGE active active TINYINT(1) NOT NULL DEFAULT \'1\', CHANGE label naam VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tw_softdrugs ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW(), CHANGE active active TINYINT(1) NOT NULL DEFAULT \'1\', CHANGE label naam VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tw_regio ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW(), CHANGE active active TINYINT(1) NOT NULL DEFAULT \'1\', CHANGE label naam VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tw_pandeigenaartype CHANGE naam naam VARCHAR(255) NOT NULL, CHANGE active active TINYINT(1) NOT NULL DEFAULT \'1\'');
        $this->addSql('ALTER TABLE tw_alcohol ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW(), CHANGE active active TINYINT(1) NOT NULL DEFAULT \'1\', CHANGE label naam VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tw_pandeigenaar CHANGE naam naam VARCHAR(255) NOT NULL, CHANGE active active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE tw_roken ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW(), CHANGE active active TINYINT(1) NOT NULL DEFAULT \'1\', CHANGE label naam VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tw_projecten ADD active TINYINT(1) NOT NULL DEFAULT \'1\', ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW()');
        $this->addSql('ALTER TABLE tw_duurthuisloos ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW(), CHANGE active active TINYINT(1) NOT NULL DEFAULT \'1\', CHANGE label naam VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tw_ritme ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW(), CHANGE active active TINYINT(1) NOT NULL DEFAULT \'1\', CHANGE label naam VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tw_dagbesteding ADD active TINYINT(1) NOT NULL DEFAULT \'1\', ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW(), CHANGE label naam VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tw_werk ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW(), CHANGE active active TINYINT(1) NOT NULL DEFAULT \'1\', CHANGE label naam VARCHAR(255) NOT NULL');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE tw_alcohol DROP created, DROP modified, CHANGE active active TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE naam label VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE tw_dagbesteding DROP active, DROP created, DROP modified, CHANGE naam label VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE tw_deelnemers ADD kwijtschelding TINYINT(1) DEFAULT NULL, CHANGE project_id project_id INT DEFAULT NULL, CHANGE intakeStatus_id intakeStatus_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tw_duurthuisloos ADD minVal INT DEFAULT NULL, ADD maxVal INT DEFAULT NULL, DROP created, DROP modified, CHANGE active active TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE naam label VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE tw_huisdieren DROP active, DROP created, DROP modified, CHANGE naam label VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE tw_inkomen DROP created, DROP modified, CHANGE active active TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE naam label VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE tw_moscreening DROP created, DROP modified, CHANGE active active TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE naam label VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE tw_pandeigenaar CHANGE naam naam VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE active active TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE tw_pandeigenaartype CHANGE naam naam VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE active active TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE tw_projecten DROP active, DROP created, DROP modified');
        $this->addSql('ALTER TABLE tw_regio DROP created, DROP modified, CHANGE active active TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE naam label VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE tw_ritme DROP created, DROP modified, CHANGE active active TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE naam label VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE tw_roken DROP created, DROP modified, CHANGE active active TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE naam label VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE tw_softdrugs DROP created, DROP modified, CHANGE active active TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE naam label VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE tw_traplopen DROP created, DROP modified, CHANGE active active TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE naam label VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE tw_werk DROP created, DROP modified, CHANGE active active TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE naam label VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');


    }
}
