<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170213133953 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vrijwilligers ADD CONSTRAINT FK_F0C4D2373D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_F0C4D2373D707F64 ON vrijwilligers (medewerker_id)');

        $this->addSql('ALTER TABLE vrijwilligers ADD CONSTRAINT FK_F0C4D2371C729A47 FOREIGN KEY (geslacht_id) REFERENCES geslachten (id)');
        $this->addSql('CREATE INDEX IDX_F0C4D2371C729A47 ON vrijwilligers (geslacht_id)');

        $this->addSql('ALTER TABLE vrijwilligers ADD CONSTRAINT FK_F0C4D2371994904A FOREIGN KEY (land_id) REFERENCES landen (id)');
        $this->addSql('CREATE INDEX IDX_F0C4D2371994904A ON vrijwilligers (land_id)');

        $this->addSql('ALTER TABLE vrijwilligers ADD CONSTRAINT FK_F0C4D237CECBFEB7 FOREIGN KEY (nationaliteit_id) REFERENCES nationaliteiten (id)');
        $this->addSql('CREATE INDEX IDX_F0C4D237CECBFEB7 ON vrijwilligers (nationaliteit_id)');

        $this->addSql('ALTER TABLE klanten ADD CONSTRAINT FK_F538C5BC1C729A47 FOREIGN KEY (geslacht_id) REFERENCES geslachten (id)');
        $this->addSql('CREATE INDEX IDX_F538C5BC1C729A47 ON klanten (geslacht_id)');

        $this->addSql('ALTER TABLE klanten ADD CONSTRAINT FK_F538C5BCCECBFEB7 FOREIGN KEY (nationaliteit_id) REFERENCES nationaliteiten (id)');
        $this->addSql('CREATE INDEX IDX_F538C5BCCECBFEB7 ON klanten (nationaliteit_id)');

        $this->addSql('ALTER TABLE iz_deelnemers ADD CONSTRAINT FK_89B5B51CFBE387F6 FOREIGN KEY (iz_afsluiting_id) REFERENCES iz_afsluitingen (id)');
        $this->addSql('CREATE INDEX IDX_89B5B51CFBE387F6 ON iz_deelnemers (iz_afsluiting_id)');

        $this->addSql('ALTER TABLE iz_intakes ADD CONSTRAINT FK_11EFC53D3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_11EFC53D3D707F64 ON iz_intakes (medewerker_id)');

        $this->addSql('ALTER TABLE iz_koppelingen ADD CONSTRAINT FK_24E5FDC23D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_24E5FDC23D707F64 ON iz_koppelingen (medewerker_id)');

        $this->addSql('ALTER TABLE iz_koppelingen ADD CONSTRAINT FK_24E5FDC2166D1F9C FOREIGN KEY (project_id) REFERENCES iz_projecten (id)');
        $this->addSql('CREATE INDEX IDX_24E5FDC2166D1F9C ON iz_koppelingen (project_id)');

        $this->addSql('ALTER TABLE iz_koppelingen ADD CONSTRAINT FK_24E5FDC28B6598D9 FOREIGN KEY (iz_eindekoppeling_id) REFERENCES iz_eindekoppelingen (id)');
        $this->addSql('CREATE INDEX IDX_24E5FDC28B6598D9 ON iz_koppelingen (iz_eindekoppeling_id)');

        $this->addSql('ALTER TABLE iz_koppelingen ADD CONSTRAINT FK_24E5FDC2D3124B3F FOREIGN KEY (iz_deelnemer_id) REFERENCES iz_deelnemers (id)');
        $this->addSql('CREATE INDEX IDX_24E5FDC2D3124B3F ON iz_koppelingen (iz_deelnemer_id)');

        $this->addSql('ALTER TABLE groepsactiviteiten_intakes ADD CONSTRAINT FK_843277B64BCC47A FOREIGN KEY (groepsactiviteiten_afsluiting_id) REFERENCES groepsactiviteiten_afsluitingen (id)');
        $this->addSql('CREATE INDEX IDX_843277B64BCC47A ON groepsactiviteiten_intakes (groepsactiviteiten_afsluiting_id)');

        $this->addSql('ALTER TABLE groepsactiviteiten_groepen_klanten ADD CONSTRAINT FK_E248EC8D68AE5B4C FOREIGN KEY (groepsactiviteiten_groep_id) REFERENCES groepsactiviteiten_groepen (id)');
        $this->addSql('CREATE INDEX IDX_E248EC8D68AE5B4C ON groepsactiviteiten_groepen_klanten (groepsactiviteiten_groep_id)');

        $this->addSql('ALTER TABLE groepsactiviteiten_groepen_klanten ADD CONSTRAINT FK_E248EC8D3C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('CREATE INDEX IDX_E248EC8D3C427B2F ON groepsactiviteiten_groepen_klanten (klant_id)');

        $this->addSql('ALTER TABLE groepsactiviteiten_groepen_klanten ADD CONSTRAINT FK_E248EC8D248D162C FOREIGN KEY (groepsactiviteiten_reden_id) REFERENCES groepsactiviteiten_redenen (id)');
        $this->addSql('CREATE INDEX IDX_E248EC8D248D162C ON groepsactiviteiten_groepen_klanten (groepsactiviteiten_reden_id)');

        $this->addSql('ALTER TABLE groepsactiviteiten_verslagen ADD CONSTRAINT FK_BF289BE03D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_BF289BE03D707F64 ON groepsactiviteiten_verslagen (medewerker_id)');

        $this->addSql('ALTER TABLE groepsactiviteiten_vrijwilligers ADD CONSTRAINT FK_78A2C7E476B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES vrijwilligers (id)');
        $this->addSql('CREATE INDEX IDX_78A2C7E476B43BDC ON groepsactiviteiten_vrijwilligers (vrijwilliger_id)');

        $this->addSql('ALTER TABLE groepsactiviteiten_klanten ADD CONSTRAINT FK_560B17693C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('CREATE INDEX IDX_560B17693C427B2F ON groepsactiviteiten_klanten (klant_id)');

        $this->addSql('ALTER TABLE groepsactiviteiten_klanten ADD CONSTRAINT FK_560B17695BF7B988 FOREIGN KEY (groepsactiviteit_id) REFERENCES groepsactiviteiten (id)');
        $this->addSql('CREATE INDEX IDX_560B17695BF7B988 ON groepsactiviteiten_klanten (groepsactiviteit_id)');

        $this->addSql('ALTER TABLE groepsactiviteiten ADD CONSTRAINT FK_9DB0AE2768AE5B4C FOREIGN KEY (groepsactiviteiten_groep_id) REFERENCES groepsactiviteiten_groepen (id)');
        $this->addSql('CREATE INDEX IDX_9DB0AE2768AE5B4C ON groepsactiviteiten (groepsactiviteiten_groep_id)');

        $this->addSql('ALTER TABLE groepsactiviteiten_groepen_vrijwilligers ADD CONSTRAINT FK_81655E2368AE5B4C FOREIGN KEY (groepsactiviteiten_groep_id) REFERENCES groepsactiviteiten_groepen (id)');
        $this->addSql('CREATE INDEX IDX_81655E2368AE5B4C ON groepsactiviteiten_groepen_vrijwilligers (groepsactiviteiten_groep_id)');

        $this->addSql('ALTER TABLE groepsactiviteiten_groepen_vrijwilligers ADD CONSTRAINT FK_81655E2376B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES vrijwilligers (id)');
        $this->addSql('CREATE INDEX IDX_81655E2376B43BDC ON groepsactiviteiten_groepen_vrijwilligers (vrijwilliger_id)');

        $this->addSql('ALTER TABLE groepsactiviteiten_groepen_vrijwilligers ADD CONSTRAINT FK_81655E23248D162C FOREIGN KEY (groepsactiviteiten_reden_id) REFERENCES groepsactiviteiten_redenen (id)');
        $this->addSql('CREATE INDEX IDX_81655E23248D162C ON groepsactiviteiten_groepen_vrijwilligers (groepsactiviteiten_reden_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
