<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519142448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inloop_access_fields (id INT NOT NULL, verblijfstatus_id INT DEFAULT NULL, intakelocatie_id INT DEFAULT NULL, gebruikersruimte_id INT DEFAULT NULL, intake_datum DATE DEFAULT NULL, toegang_inloophuis TINYINT(1) DEFAULT NULL, overigen_toegang_van DATE DEFAULT NULL, INDEX IDX_2ABBA00D48D0634A (verblijfstatus_id), INDEX IDX_2ABBA00D55E45319 (intakelocatie_id), INDEX IDX_2ABBA00DFB34A317 (gebruikersruimte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inloop_access_fields_locaties (accessfields_id INT NOT NULL, locatie_id INT NOT NULL, INDEX IDX_36F075296F81BDFF (accessfields_id), INDEX IDX_36F075294947630C (locatie_id), PRIMARY KEY(accessfields_id, locatie_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inloop_access_fields ADD CONSTRAINT FK_2ABBA00D48D0634A FOREIGN KEY (verblijfstatus_id) REFERENCES verblijfstatussen (id)');
        $this->addSql('ALTER TABLE inloop_access_fields ADD CONSTRAINT FK_2ABBA00D55E45319 FOREIGN KEY (intakelocatie_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE inloop_access_fields ADD CONSTRAINT FK_2ABBA00DFB34A317 FOREIGN KEY (gebruikersruimte_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE inloop_access_fields_locaties ADD CONSTRAINT FK_36F075296F81BDFF FOREIGN KEY (accessfields_id) REFERENCES inloop_access_fields (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inloop_access_fields_locaties ADD CONSTRAINT FK_36F075294947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id) ON DELETE CASCADE');

        $this->addSql('INSERT INTO inloop_access_fields (
                id,
                intake_datum,
                verblijfstatus_id,
                intakelocatie_id,
                toegang_inloophuis,
                overigen_toegang_van,
                gebruikersruimte_id
            )
            SELECT
                i.id,
                i.datum_intake,
                i.verblijfstatus_id,
                i.locatie2_id,
                i.toegang_inloophuis,
                i.overigen_toegang_van,
                i.locatie1_id
            FROM intakes i
        ');

        // Migrate specifieke_locaties
        $this->addSql('INSERT INTO inloop_access_fields_locaties (accessfields_id, locatie_id) 
            (
                SELECT
                    la.intake_id,
                    la.locatie_id 
                FROM locaties_accessintakes la
                JOIN inloop_access_fields iaf 
                    ON intake_id = iaf.id
            )');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inloop_access_fields DROP FOREIGN KEY FK_2ABBA00D48D0634A');
        $this->addSql('ALTER TABLE inloop_access_fields DROP FOREIGN KEY FK_2ABBA00D55E45319');
        $this->addSql('ALTER TABLE inloop_access_fields DROP FOREIGN KEY FK_2ABBA00DFB34A317');
        $this->addSql('ALTER TABLE inloop_access_fields_locaties DROP FOREIGN KEY FK_36F075296F81BDFF');
        $this->addSql('ALTER TABLE inloop_access_fields_locaties DROP FOREIGN KEY FK_36F075294947630C');
        $this->addSql('DROP TABLE inloop_access_fields');
        $this->addSql('DROP TABLE inloop_access_fields_locaties');
    }
}
