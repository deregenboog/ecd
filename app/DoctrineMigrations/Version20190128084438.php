<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190128084438 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE klanten DROP INDEX FK_F538C5BC8B2671BD, ADD UNIQUE INDEX UNIQ_F538C5BC8B2671BD (huidigeStatus_id)');
        $this->addSql('ALTER TABLE klanten DROP FOREIGN KEY FK_F538C5BC1D103C3F');
        $this->addSql('ALTER TABLE klanten ADD CONSTRAINT FK_F538C5BCC1BEA629 FOREIGN KEY (merged_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE klanten ADD CONSTRAINT FK_F538C5BC3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE klanten ADD CONSTRAINT FK_F538C5BC1994904A FOREIGN KEY (land_id) REFERENCES landen (id)');
        $this->addSql('CREATE INDEX IDX_F538C5BCC1BEA629 ON klanten (merged_id)');
        $this->addSql('DROP INDEX fk_f538c5bc1d103c3f ON klanten');
        $this->addSql('CREATE INDEX IDX_F538C5BC1D103C3F ON klanten (laste_intake_id)');
        $this->addSql('ALTER TABLE klanten ADD CONSTRAINT FK_F538C5BC1D103C3F FOREIGN KEY (laste_intake_id) REFERENCES intakes (id)');
        $this->addSql('DROP INDEX idx_klanten_geboortedatum ON vrijwilligers');
        $this->addSql('CREATE INDEX idx_vrijwilligers_geboortedatum ON vrijwilligers (geboortedatum)');
        $this->addSql('ALTER TABLE opmerkingen ADD CONSTRAINT FK_C2C23B293C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE opmerkingen ADD CONSTRAINT FK_C2C23B29BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorieen (id)');
        $this->addSql('CREATE INDEX IDX_C2C23B29BCF5E72D ON opmerkingen (categorie_id)');
        $this->addSql('ALTER TABLE inloop_vrijwilligers DROP FOREIGN KEY FK_56110480D8471945');
        $this->addSql('DROP INDEX idx_56110480d8471945 ON inloop_vrijwilligers');
        $this->addSql('CREATE INDEX IDX_561104804C676E6B ON inloop_vrijwilligers (binnen_via_id)');
        $this->addSql('ALTER TABLE inloop_vrijwilligers ADD CONSTRAINT FK_56110480D8471945 FOREIGN KEY (binnen_via_id) REFERENCES inloop_binnen_via (id)');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AECD268935 FOREIGN KEY (locatie2_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AEDF9326DB FOREIGN KEY (locatie1_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AE759AEE50 FOREIGN KEY (locatie3_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AEEB38826A FOREIGN KEY (legitimatie_id) REFERENCES legitimaties (id)');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AED106567A FOREIGN KEY (infobaliedoelgroep_id) REFERENCES infobaliedoelgroepen (id)');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AE6DF0864 FOREIGN KEY (primaireProblematiek_id) REFERENCES verslavingen (id)');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AE694ADD79 FOREIGN KEY (primaireproblematieksfrequentie_id) REFERENCES verslavingsfrequenties (id)');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AEDC542336 FOREIGN KEY (primaireproblematieksperiode_id) REFERENCES verslavingsperiodes (id)');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AE4B616C78 FOREIGN KEY (verslavingsfrequentie_id) REFERENCES verslavingsfrequenties (id)');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AE1C06E73B FOREIGN KEY (verslavingsperiode_id) REFERENCES verslavingsperiodes (id)');
        $this->addSql('CREATE INDEX IDX_AB70F5AECD268935 ON intakes (locatie2_id)');
        $this->addSql('CREATE INDEX IDX_AB70F5AEDF9326DB ON intakes (locatie1_id)');
        $this->addSql('CREATE INDEX IDX_AB70F5AE759AEE50 ON intakes (locatie3_id)');
        $this->addSql('CREATE INDEX IDX_AB70F5AEEB38826A ON intakes (legitimatie_id)');
        $this->addSql('CREATE INDEX IDX_AB70F5AED106567A ON intakes (infobaliedoelgroep_id)');
        $this->addSql('CREATE INDEX IDX_AB70F5AE6DF0864 ON intakes (primaireProblematiek_id)');
        $this->addSql('CREATE INDEX IDX_AB70F5AE694ADD79 ON intakes (primaireproblematieksfrequentie_id)');
        $this->addSql('CREATE INDEX IDX_AB70F5AEDC542336 ON intakes (primaireproblematieksperiode_id)');
        $this->addSql('CREATE INDEX IDX_AB70F5AE4B616C78 ON intakes (verslavingsfrequentie_id)');
        $this->addSql('CREATE INDEX IDX_AB70F5AE1C06E73B ON intakes (verslavingsperiode_id)');
        $this->addSql('DROP INDEX idx_intakes_woonsituatie_id ON intakes');
        $this->addSql('CREATE INDEX IDX_AB70F5AEC7424D3F ON intakes (woonsituatie_id)');
        $this->addSql('ALTER TABLE inkomens_intakes ADD CONSTRAINT FK_66CE79C0DE7E5B0 FOREIGN KEY (inkomen_id) REFERENCES inkomens (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_66CE79C0733DE450 ON inkomens_intakes (intake_id)');
        $this->addSql('CREATE INDEX IDX_66CE79C0DE7E5B0 ON inkomens_intakes (inkomen_id)');
        $this->addSql('ALTER TABLE intakes_primaireproblematieksgebruikswijzen ADD CONSTRAINT FK_5BE550D2733DE450 FOREIGN KEY (intake_id) REFERENCES intakes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intakes_primaireproblematieksgebruikswijzen ADD CONSTRAINT FK_5BE550D2DB7A239E FOREIGN KEY (primaireproblematieksgebruikswijze_id) REFERENCES verslavingsgebruikswijzen (id)');
        $this->addSql('CREATE INDEX IDX_5BE550D2733DE450 ON intakes_primaireproblematieksgebruikswijzen (intake_id)');
        $this->addSql('CREATE INDEX IDX_5BE550D2DB7A239E ON intakes_primaireproblematieksgebruikswijzen (primaireproblematieksgebruikswijze_id)');
        $this->addSql('ALTER TABLE intakes_verslavingen ADD CONSTRAINT FK_AD93CFF3733DE450 FOREIGN KEY (intake_id) REFERENCES intakes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intakes_verslavingen ADD CONSTRAINT FK_AD93CFF310258C8 FOREIGN KEY (verslaving_id) REFERENCES verslavingen (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_AD93CFF3733DE450 ON intakes_verslavingen (intake_id)');
        $this->addSql('CREATE INDEX IDX_AD93CFF310258C8 ON intakes_verslavingen (verslaving_id)');
        $this->addSql('ALTER TABLE intakes_verslavingsgebruikswijzen ADD CONSTRAINT FK_A2AFE8FE733DE450 FOREIGN KEY (intake_id) REFERENCES intakes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intakes_verslavingsgebruikswijzen ADD CONSTRAINT FK_A2AFE8FE8BDA2F32 FOREIGN KEY (verslavingsgebruikswijze_id) REFERENCES verslavingsgebruikswijzen (id)');
        $this->addSql('CREATE INDEX IDX_A2AFE8FE733DE450 ON intakes_verslavingsgebruikswijzen (intake_id)');
        $this->addSql('CREATE INDEX IDX_A2AFE8FE8BDA2F32 ON intakes_verslavingsgebruikswijzen (verslavingsgebruikswijze_id)');
        $this->addSql('ALTER TABLE instanties_intakes ADD CONSTRAINT FK_9D7459552A1C57EF FOREIGN KEY (instantie_id) REFERENCES instanties (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_9D745955733DE450 ON instanties_intakes (intake_id)');
        $this->addSql('CREATE INDEX IDX_9D7459552A1C57EF ON instanties_intakes (instantie_id)');
        $this->addSql('ALTER TABLE iz_koppeling_doelgroep DROP FOREIGN KEY FK_8E6CE05D5C6E6B2');
        $this->addSql('ALTER TABLE iz_koppeling_doelgroep ADD CONSTRAINT FK_8E6CE05D5C6E6B2 FOREIGN KEY (koppeling_id) REFERENCES iz_koppelingen (id)');
        $this->addSql('DROP INDEX idx_iz_deelnemers_persoon ON iz_deelnemers');
        $this->addSql('DROP INDEX iz_deelnemers_iz_projecten_iz_project_id ON iz_deelnemers_iz_projecten');
        $this->addSql('DROP INDEX iz_deelnemers_iz_projecten_id_deelnemr ON iz_deelnemers_iz_projecten');
        $this->addSql('DROP INDEX UNIQ_178943F7FC4DB9384AD26064 ON iz_succesindicatoren');
        $this->addSql('ALTER TABLE verslagen ADD CONSTRAINT FK_2BBABA714947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE verslagen ADD CONSTRAINT FK_2BBABA71D3899023 FOREIGN KEY (contactsoort_id) REFERENCES contactsoorts (id)');
        $this->addSql('CREATE INDEX IDX_2BBABA71D3899023 ON verslagen (contactsoort_id)');
        $this->addSql('ALTER TABLE verslaginfos ADD CONSTRAINT FK_3D2FCA833C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE verslaginfos ADD CONSTRAINT FK_3D2FCA831B81E585 FOREIGN KEY (casemanager_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE verslaginfos ADD CONSTRAINT FK_3D2FCA831EC41507 FOREIGN KEY (trajectbegeleider_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_3D2FCA831B81E585 ON verslaginfos (casemanager_id)');
        $this->addSql('CREATE INDEX IDX_3D2FCA831EC41507 ON verslaginfos (trajectbegeleider_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3D2FCA833C427B2F ON verslaginfos (klant_id)');
        $this->addSql('ALTER TABLE inventarisaties_verslagen ADD CONSTRAINT FK_45A2DE1430AF6145 FOREIGN KEY (inventarisatie_id) REFERENCES inventarisaties (id)');
        $this->addSql('ALTER TABLE inventarisaties_verslagen ADD CONSTRAINT FK_45A2DE14D8291178 FOREIGN KEY (doorverwijzer_id) REFERENCES doorverwijzers (id)');
        $this->addSql('CREATE INDEX IDX_45A2DE14D949475D ON inventarisaties_verslagen (verslag_id)');
        $this->addSql('CREATE INDEX IDX_45A2DE1430AF6145 ON inventarisaties_verslagen (inventarisatie_id)');
        $this->addSql('CREATE INDEX IDX_45A2DE14D8291178 ON inventarisaties_verslagen (doorverwijzer_id)');
        $this->addSql('ALTER TABLE inventarisaties ADD CONSTRAINT FK_1343F8F1727ACA70 FOREIGN KEY (parent_id) REFERENCES inventarisaties (id)');
        $this->addSql('CREATE INDEX IDX_1343F8F1727ACA70 ON inventarisaties (parent_id)');
        $this->addSql('ALTER TABLE oekklant_oekdossierstatus DROP FOREIGN KEY FK_1EF9C0A61833A719');
        $this->addSql('ALTER TABLE oekklant_oekdossierstatus DROP FOREIGN KEY FK_1EF9C0A6B689C3C1');
        $this->addSql('ALTER TABLE oekklant_oekdossierstatus ADD CONSTRAINT FK_1EF9C0A61833A719 FOREIGN KEY (oekklant_id) REFERENCES oek_klanten (id)');
        $this->addSql('ALTER TABLE oekklant_oekdossierstatus ADD CONSTRAINT FK_1EF9C0A6B689C3C1 FOREIGN KEY (oekdossierstatus_id) REFERENCES oek_dossier_statussen (id)');
    }

     public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
