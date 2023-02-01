<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230124151057 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf('mysql' != $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clip_clienten DROP FOREIGN KEY FK_B7F4C67EFB02B9C2');
        $this->addSql('ALTER TABLE clip_clienten ADD CONSTRAINT FK_B7F4C67EFB02B9C2 FOREIGN KEY (postcodegebied) REFERENCES ggw_gebieden (naam)');

        $this->addSql('ALTER TABLE inloop_deelnames DROP FOREIGN KEY FK_CFB194F3B280D297');
        $this->addSql('ALTER TABLE inloop_deelnames ADD CONSTRAINT FK_CFB194F3B280D297 FOREIGN KEY (inloop_vrijwilliger_id) REFERENCES inloop_vrijwilligers (id)');

        $this->addSql('ALTER TABLE inventarisaties_verslagen ADD CONSTRAINT FK_45A2DE14D949475D FOREIGN KEY (verslag_id) REFERENCES verslagen (id)');

        $this->addSql('DROP INDEX id ON geslachten');

        $this->addSql('ALTER TABLE hs_betalingen DROP FOREIGN KEY FK_EADEA9FFC35D3E');
        $this->addSql('ALTER TABLE hs_betalingen ADD CONSTRAINT FK_EADEA9FFC35D3E FOREIGN KEY (factuur_id) REFERENCES hs_facturen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_declaraties ADD CONSTRAINT FK_AF23D292C35D3E FOREIGN KEY (factuur_id) REFERENCES hs_facturen (id)');
        $this->addSql('ALTER TABLE hs_declaraties DROP FOREIGN KEY FK_AF23D292BA5374AF');
        $this->addSql('ALTER TABLE hs_declaraties ADD CONSTRAINT FK_AF23D292BA5374AF FOREIGN KEY (klus_id) REFERENCES hs_klussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_facturen DROP FOREIGN KEY FK_31669C163C427B2F');
        $this->addSql('ALTER TABLE hs_facturen ADD CONSTRAINT FK_31669C163C427B2F FOREIGN KEY (klant_id) REFERENCES hs_klanten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_klanten CHANGE status status INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE hs_klussen DROP FOREIGN KEY FK_3E9A80CF3C427B2F');
        $this->addSql('ALTER TABLE hs_klussen ADD CONSTRAINT FK_3E9A80CF3C427B2F FOREIGN KEY (klant_id) REFERENCES hs_klanten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_registraties DROP FOREIGN KEY FK_62041BC2C35D3E');
        $this->addSql('ALTER TABLE hs_registraties ADD CONSTRAINT FK_62041BC2C35D3E FOREIGN KEY (factuur_id) REFERENCES hs_facturen (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE inkomens_intakes MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE inkomens_intakes DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE inkomens_intakes DROP id, DROP created, DROP modified');
        $this->addSql('ALTER TABLE inkomens_intakes ADD PRIMARY KEY (intake_id, inkomen_id)');

        $this->addSql('ALTER TABLE intakes_primaireproblematieksgebruikswijzen MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE intakes_primaireproblematieksgebruikswijzen DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE intakes_primaireproblematieksgebruikswijzen DROP id, DROP created, DROP modified');
        $this->addSql('ALTER TABLE intakes_primaireproblematieksgebruikswijzen ADD PRIMARY KEY (intake_id, primaireproblematieksgebruikswijze_id)');

        $this->addSql('ALTER TABLE intakes_verslavingen MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE intakes_verslavingen DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE intakes_verslavingen DROP id, DROP created, DROP modified');
        $this->addSql('ALTER TABLE intakes_verslavingen ADD PRIMARY KEY (intake_id, verslaving_id)');

        $this->addSql('ALTER TABLE intakes_verslavingsgebruikswijzen MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE intakes_verslavingsgebruikswijzen DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE intakes_verslavingsgebruikswijzen DROP id, DROP created, DROP modified');
        $this->addSql('ALTER TABLE intakes_verslavingsgebruikswijzen ADD PRIMARY KEY (intake_id, verslavingsgebruikswijze_id)');

        $this->addSql('ALTER TABLE instanties_intakes MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE instanties_intakes DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE instanties_intakes DROP id, DROP created, DROP modified');
        $this->addSql('ALTER TABLE instanties_intakes ADD PRIMARY KEY (intake_id, instantie_id)');

        $this->addSql('ALTER TABLE schorsingen_redenen MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE schorsingen_redenen DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE schorsingen_redenen DROP id, DROP created, DROP modified');
        $this->addSql('ALTER TABLE schorsingen_redenen ADD PRIMARY KEY (schorsing_id, reden_id)');

        $this->addSql('ALTER TABLE iz_deelnemers_iz_projecten ADD CONSTRAINT FK_65A512DBD3124B3F FOREIGN KEY (iz_deelnemer_id) REFERENCES iz_deelnemers (id)');
        $this->addSql('ALTER TABLE iz_intakes ADD CONSTRAINT FK_11EFC53DD3124B3F FOREIGN KEY (iz_deelnemer_id) REFERENCES iz_deelnemers (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_11EFC53DD3124B3F ON iz_intakes (iz_deelnemer_id)');
        $this->addSql('ALTER TABLE iz_verslagen ADD CONSTRAINT FK_570FE99B8B2EFA2C FOREIGN KEY (iz_koppeling_id) REFERENCES iz_koppelingen (id)');
        $this->addSql('ALTER TABLE iz_verslagen ADD CONSTRAINT FK_570FE99BD3124B3F FOREIGN KEY (iz_deelnemer_id) REFERENCES iz_deelnemers (id)');

        $this->addSql('ALTER TABLE iz_deelnemers_iz_projecten MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE iz_deelnemers_iz_projecten DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE iz_deelnemers_iz_projecten DROP id, DROP created, DROP modified');
        $this->addSql('ALTER TABLE iz_deelnemers_iz_projecten ADD PRIMARY KEY (iz_deelnemer_id, iz_project_id)');

        $this->addSql('ALTER TABLE mw_deelnames DROP FOREIGN KEY FK_59035D7541D2A6EF');
        $this->addSql('ALTER TABLE mw_deelnames ADD CONSTRAINT FK_59035D75459F3233 FOREIGN KEY (mwTraining_id) REFERENCES mw_training (id)');
        $this->addSql('ALTER TABLE mw_deelnames RENAME INDEX idx_59035d7541d2a6ef TO IDX_59035D75459F3233');
        $this->addSql('ALTER TABLE mw_vrijwilligers DROP locatie_id');

        $this->addSql('ALTER TABLE pfo_clienten_supportgroups MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE pfo_clienten_supportgroups DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE pfo_clienten_supportgroups DROP id, DROP created, DROP modified');
        $this->addSql('ALTER TABLE pfo_clienten_supportgroups ADD PRIMARY KEY (pfo_supportgroup_client_id, pfo_client_id)');

        $this->addSql('ALTER TABLE pfo_clienten_verslagen MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE pfo_clienten_verslagen DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE pfo_clienten_verslagen DROP id, DROP created, DROP modified');
        $this->addSql('ALTER TABLE pfo_clienten_verslagen ADD PRIMARY KEY (pfo_verslag_id, pfo_client_id)');

        $this->addSql('ALTER TABLE tw_deelnames DROP FOREIGN KEY FK_C8B28A18629A95E');
        $this->addSql('DROP INDEX IDX_B0B3FDE19D1883DD ON tw_deelnames');
        $this->addSql('ALTER TABLE tw_huurverzoeken DROP FOREIGN KEY FK_B59AA1213C427B2F');
        $this->addSql('DROP INDEX IDX_B59AA1219E4835DA ON tw_huurverzoeken');
        $this->addSql('DROP INDEX IDX_E4317256C0B11400 ON tw_deelnemers');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_document DROP FOREIGN KEY FK_7B9A48A7870B85BC');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_document DROP FOREIGN KEY FK_7B9A48A7C33F7837');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
