<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180425092513 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        // @todo remove polymorphic association
        // Velden model en foreign_key worden door Doctrine event subscriber
        // ingevuld en moeten dus in eerste instantie leeg kunnen zijn.
        $this->addSql('ALTER TABLE zrm_reports CHANGE model model VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE zrm_reports CHANGE foreign_key foreign_key INT(11) DEFAULT NULL');

        // verwijder ZRMs waarbij niks is ingevuld
        $this->addSql('
            DELETE FROM zrm_reports
            WHERE inkomen IS NULL
            AND dagbesteding IS NULL
            AND huisvesting IS NULL
            AND gezinsrelaties IS NULL
            AND geestelijke_gezondheid IS NULL
            AND fysieke_gezondheid IS NULL
            AND verslaving IS NULL
            AND adl_vaardigheden IS NULL
            AND sociaal_netwerk IS NULL
            AND maatschappelijke_participatie IS NULL
            AND justitie IS NULL
            AND financien IS NULL
            AND werk_opleiding IS NULL
            AND tijdsbesteding IS NULL
            AND huiselijke_relaties IS NULL
            AND lichamelijke_gezondheid IS NULL
            AND middelengebruik IS NULL
            AND basale_adl IS NULL
            AND instrumentele_adl IS NULL
        ');

        $this->addSql('CREATE TABLE iz_intake_zrm (intake_id INT NOT NULL, zrm_id INT NOT NULL, INDEX IDX_C84288B3733DE450 (intake_id), UNIQUE INDEX UNIQ_C84288B3C8250F57 (zrm_id), PRIMARY KEY(intake_id, zrm_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iz_intake_zrm ADD CONSTRAINT FK_C84288B3733DE450 FOREIGN KEY (intake_id) REFERENCES iz_intakes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_intake_zrm ADD CONSTRAINT FK_C84288B3C8250F57 FOREIGN KEY (zrm_id) REFERENCES zrm_reports (id) ON DELETE CASCADE');
        $this->addSql("
            INSERT IGNORE INTO iz_intake_zrm (intake_id, zrm_id)
                SELECT i.id, zrm.id
                FROM iz_intakes i
                INNER JOIN zrm_reports zrm ON i.id = zrm.foreign_key AND zrm.model = 'IzIntake'
        ");

        $this->addSql('CREATE TABLE inloop_intake_zrm (intake_id INT NOT NULL, zrm_id INT NOT NULL, INDEX IDX_92197717733DE450 (intake_id), UNIQUE INDEX UNIQ_92197717C8250F57 (zrm_id), PRIMARY KEY(intake_id, zrm_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inloop_intake_zrm ADD CONSTRAINT FK_92197717733DE450 FOREIGN KEY (intake_id) REFERENCES intakes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inloop_intake_zrm ADD CONSTRAINT FK_92197717C8250F57 FOREIGN KEY (zrm_id) REFERENCES zrm_reports (id) ON DELETE CASCADE');
        $this->addSql("
            INSERT IGNORE INTO inloop_intake_zrm (intake_id, zrm_id)
                SELECT i.id, zrm.id
                FROM intakes i
                INNER JOIN zrm_reports zrm ON i.id = zrm.foreign_key AND zrm.model = 'Intake'
        ");

        $this->addSql('CREATE TABLE ga_gaklantintake_zrm (gaklantintake_id INT NOT NULL, zrm_id INT NOT NULL, INDEX IDX_D68056715FA93F88 (gaklantintake_id), UNIQUE INDEX UNIQ_D6805671C8250F57 (zrm_id), PRIMARY KEY(gaklantintake_id, zrm_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ga_gaklantintake_zrm ADD CONSTRAINT FK_D68056715FA93F88 FOREIGN KEY (gaklantintake_id) REFERENCES groepsactiviteiten_intakes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ga_gaklantintake_zrm ADD CONSTRAINT FK_D6805671C8250F57 FOREIGN KEY (zrm_id) REFERENCES zrm_reports (id) ON DELETE CASCADE');
        $this->addSql("
            INSERT IGNORE INTO ga_gaklantintake_zrm (gaklantintake_id, zrm_id)
                SELECT i.id, zrm.id
                FROM groepsactiviteiten_intakes i
                INNER JOIN zrm_reports zrm ON i.id = zrm.foreign_key AND zrm.model = 'GroepsactiviteitenIntake'
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
