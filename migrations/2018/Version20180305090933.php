<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180305090933 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE iz_koppeling_doelgroep (
                koppeling_id INT NOT NULL,
                doelgroep_id INT NOT NULL,
                INDEX IDX_8E6CE05D5C6E6B2 (koppeling_id),
                INDEX IDX_8E6CE05DE5A2DFCE (doelgroep_id),
                PRIMARY KEY(koppeling_id, doelgroep_id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iz_koppeling_doelgroep
            ADD CONSTRAINT FK_8E6CE05D5C6E6B2 FOREIGN KEY (koppeling_id) REFERENCES iz_koppelingen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_koppeling_doelgroep
            ADD CONSTRAINT FK_8E6CE05DE5A2DFCE FOREIGN KEY (doelgroep_id) REFERENCES iz_doelgroepen (id) ON DELETE CASCADE');

        $this->addSql('CREATE TABLE iz_hulpvraag_hulpvraagsoort (
                hulpvraag_id INT NOT NULL,
                hulpvraagsoort_id INT NOT NULL,
                INDEX IDX_AB89A000A8450D8C (hulpvraag_id),
                INDEX IDX_AB89A000950213F (hulpvraagsoort_id),
                PRIMARY KEY(hulpvraag_id, hulpvraagsoort_id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iz_hulpvraag_hulpvraagsoort
            ADD CONSTRAINT FK_AB89A000A8450D8C FOREIGN KEY (hulpvraag_id) REFERENCES iz_koppelingen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_hulpvraag_hulpvraagsoort
            ADD CONSTRAINT FK_AB89A000950213F FOREIGN KEY (hulpvraagsoort_id) REFERENCES iz_hulpvraagsoorten (id) ON DELETE CASCADE');

        $this->addSql('CREATE TABLE iz_hulpaanbod_hulpvraagsoort (
                hulpaanbod_id INT NOT NULL,
                hulpvraagsoort_id INT NOT NULL,
                INDEX IDX_D839A990B42008F3 (hulpaanbod_id),
                INDEX IDX_D839A990950213F (hulpvraagsoort_id),
                PRIMARY KEY(hulpaanbod_id, hulpvraagsoort_id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iz_hulpaanbod_hulpvraagsoort
            ADD CONSTRAINT FK_D839A990B42008F3 FOREIGN KEY (hulpaanbod_id) REFERENCES iz_koppelingen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_hulpaanbod_hulpvraagsoort
            ADD CONSTRAINT FK_D839A990950213F FOREIGN KEY (hulpvraagsoort_id) REFERENCES iz_hulpvraagsoorten (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE iz_koppelingen
            ADD info LONGTEXT DEFAULT NULL,
            ADD dagdeel VARCHAR(255) DEFAULT NULL,
            ADD spreekt_nederlands TINYINT(1) DEFAULT 1,
            ADD primaireHulpvraagsoort_id INT DEFAULT NULL,
            ADD voorkeur_voor_nederlands TINYINT(1) DEFAULT NULL,
            ADD voorkeurGeslacht_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE iz_koppelingen
            ADD CONSTRAINT FK_24E5FDC2C9788B0A FOREIGN KEY (voorkeurGeslacht_id) REFERENCES geslachten (id)');
        $this->addSql('ALTER TABLE iz_koppelingen
            ADD CONSTRAINT FK_24E5FDC222638DF1 FOREIGN KEY (primaireHulpvraagsoort_id) REFERENCES iz_hulpvraagsoorten (id)');
        $this->addSql('CREATE INDEX IDX_24E5FDC2C9788B0A ON iz_koppelingen (voorkeurGeslacht_id)');
        $this->addSql('CREATE INDEX IDX_24E5FDC28B2EFA2C ON iz_koppelingen (iz_koppeling_id)');
        $this->addSql('CREATE INDEX IDX_24E5FDC222638DF1 ON iz_koppelingen (primaireHulpvraagsoort_id)');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
