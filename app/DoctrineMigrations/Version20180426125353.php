<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180426125353 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE iz_verslagen SET medewerker_id = 20655 WHERE medewerker_id = 0');

        $this->addSql('ALTER TABLE iz_verslagen ADD CONSTRAINT FK_570FE99B3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_570FE99B3D707F64 ON iz_verslagen (medewerker_id)');

        $this->addSql('ALTER TABLE iz_intervisiegroepen ADD CONSTRAINT FK_86CA227E3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_86CA227E3D707F64 ON iz_intervisiegroepen (medewerker_id)');

        $this->addSql('ALTER TABLE iz_deelnemers_iz_intervisiegroepen ADD CONSTRAINT FK_3A903EEF495B2A54 FOREIGN KEY (iz_intervisiegroep_id) REFERENCES iz_intervisiegroepen (id)');
        $this->addSql('CREATE INDEX IDX_3A903EEF495B2A54 ON iz_deelnemers_iz_intervisiegroepen (iz_intervisiegroep_id)');

        $this->addSql('ALTER TABLE iz_deelnemers_iz_intervisiegroepen ADD CONSTRAINT FK_3A903EEFD3124B3F FOREIGN KEY (iz_deelnemer_id) REFERENCES iz_deelnemers (id)');
        $this->addSql('CREATE INDEX IDX_3A903EEFD3124B3F ON iz_deelnemers_iz_intervisiegroepen (iz_deelnemer_id)');

        $this->addSql('ALTER TABLE iz_deelnemers_iz_projecten ADD CONSTRAINT FK_65A512DB56CEA1A9 FOREIGN KEY (iz_project_id) REFERENCES iz_projecten (id)');
        $this->addSql('CREATE INDEX IDX_65A512DB56CEA1A9 ON iz_deelnemers_iz_projecten (iz_project_id)');

        $this->addSql('CREATE INDEX IDX_24E5FDC2C217B9F ON iz_koppelingen (iz_vraagaanbod_id)');
        $this->addSql('CREATE INDEX IDX_570FE99BD3124B3F ON iz_verslagen (iz_deelnemer_id)');
        $this->addSql('CREATE INDEX IDX_570FE99B8B2EFA2C ON iz_verslagen (iz_koppeling_id)');
        $this->addSql('CREATE INDEX IDX_65A512DBD3124B3F ON iz_deelnemers_iz_projecten (iz_deelnemer_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_86CE34D4FC4DB938 ON iz_hulpvraagsoorten (naam)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_95359FC8FC4DB938 ON iz_doelgroepen (naam)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
