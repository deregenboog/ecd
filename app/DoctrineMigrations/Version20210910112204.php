<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210910112204 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tw_superdocumenten CHANGE class class VARCHAR(12) NOT NULL');
        $this->addSql('ALTER TABLE tw_superdocumenten RENAME INDEX idx_6e6f9fd53d707f64 TO IDX_1633B5253D707F64');
        $this->addSql('ALTER TABLE tw_coordinatoren RENAME INDEX uniq_62bccdb53d707f64 TO UNIQ_8FA922023D707F64');
        $this->addSql('ALTER TABLE tw_deelnemers DROP INDEX UNIQ_3A1E7F772BB8C0FB, ADD INDEX IDX_E43172562BB8C0FB (ambulantOndersteuner_id)');
        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_202839993C427B2F');
        $this->addSql('DROP INDEX IDX_202839993C427B2F ON tw_deelnemers');
        $this->addSql('ALTER TABLE tw_deelnemers CHANGE klant_id appKlant_id INT NOT NULL');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E43172565C217849 FOREIGN KEY (appKlant_id) REFERENCES klanten (id)');
        $this->addSql('CREATE INDEX IDX_E43172565C217849 ON tw_deelnemers (appKlant_id)');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX idx_202839993d707f64 TO IDX_E43172563D707F64');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX idx_20283999ecdad1a9 TO IDX_E4317256ECDAD1A9');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX fk_20283999c8f722f4 TO IDX_E43172564A06A057');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX idx_20283999d93d6b19 TO IDX_E4317256D93D6B19');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX idx_202839991aaf70bd TO IDX_E43172561AAF70BD');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX idx_20283999c8250f57 TO IDX_E4317256C8250F57');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX idx_20283999c0b11400 TO IDX_E4317256C0B11400');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX idx_20283999166d1f9c TO IDX_E4317256166D1F9C');
        $this->addSql('ALTER TABLE tw_deelnemer_verslag RENAME INDEX idx_f8f75d6a5dfa57a1 TO IDX_33E2C4055DFA57A1');
        $this->addSql('ALTER TABLE tw_deelnemer_verslag RENAME INDEX idx_f8f75d6ad949475d TO IDX_33E2C405D949475D');
        $this->addSql('ALTER TABLE tw_deelnemer_document RENAME INDEX idx_9ba61cc55dfa57a1 TO IDX_466075955DFA57A1');
        $this->addSql('ALTER TABLE tw_deelnemer_document RENAME INDEX idx_9ba61cc5c33f7837 TO IDX_46607595C33F7837');
        $this->addSql('ALTER TABLE tw_huurders_tw_projecten RENAME INDEX idx_48e405357776076a TO IDX_34E5855EB36F4CA5');
        $this->addSql('ALTER TABLE tw_huurders_tw_projecten RENAME INDEX idx_48e40535ff532d2c TO IDX_34E5855E3B4A66E3');
        $this->addSql('ALTER TABLE tw_superverslagen CHANGE class class VARCHAR(12) NOT NULL');
        $this->addSql('ALTER TABLE tw_superverslagen RENAME INDEX idx_762d3f773d707f64 TO IDX_2DCE6D3F3D707F64');
        $this->addSql('ALTER TABLE tw_afsluitingen CHANGE active active TINYINT(1) NOT NULL, CHANGE tonen tonen TINYINT(1) DEFAULT NULL');

        $this->addSql('ALTER TABLE tw_huuraanbiedingen RENAME INDEX idx_fa204f877e18485d TO IDX_8E0AAC377E18485D');
        $this->addSql('ALTER TABLE tw_huuraanbiedingen RENAME INDEX idx_fa204f87522cf24b TO IDX_8E0AAC37B79D9579');
        $this->addSql('ALTER TABLE tw_huuraanbiedingen RENAME INDEX idx_fa204f87ecdad1a9 TO IDX_8E0AAC37ECDAD1A9');
        $this->addSql('ALTER TABLE tw_huuraanbiedingen RENAME INDEX idx_fa204f87166d1f9c TO IDX_8E0AAC37166D1F9C');
        $this->addSql('ALTER TABLE tw_huuraanbiedingen RENAME INDEX idx_fa204f873d707f64 TO IDX_8E0AAC373D707F64');
        $this->addSql('ALTER TABLE tw_huuraanbod_verslag RENAME INDEX idx_9b2de75b656e2280 TO IDX_46EB8E0B656E2280');
        $this->addSql('ALTER TABLE tw_huuraanbod_verslag RENAME INDEX idx_9b2de75bd949475d TO IDX_46EB8E0BD949475D');
        $this->addSql('ALTER TABLE tw_huurverzoeken RENAME INDEX idx_588f4e969e4835da TO IDX_B59AA1219E4835DA');
        $this->addSql('ALTER TABLE tw_huurverzoeken RENAME INDEX idx_588f4e96ecdad1a9 TO IDX_B59AA121ECDAD1A9');
        $this->addSql('ALTER TABLE tw_huurverzoeken RENAME INDEX idx_588f4e963d707f64 TO IDX_B59AA1213D707F64');
        $this->addSql('ALTER TABLE tw_huurverzoek_verslag RENAME INDEX idx_46cb48c145da3bb7 TO IDX_2D7DDF5C45DA3BB7');
        $this->addSql('ALTER TABLE tw_huurverzoek_verslag RENAME INDEX idx_46cb48c1d949475d TO IDX_2D7DDF5CD949475D');
        $this->addSql('ALTER TABLE tw_huurverzoeken_tw_projecten RENAME INDEX idx_cdf6eebce7540572 TO IDX_1466F1BBB2BC36B2');
        $this->addSql('ALTER TABLE tw_huurverzoeken_tw_projecten RENAME INDEX idx_cdf6eebcff532d2c TO IDX_1466F1BB3B4A66E3');
        $this->addSql('ALTER TABLE tw_huurovereenkomsten CHANGE opzegbrief_verstuurd opzegbrief_verstuurd TINYINT(1) NOT NULL, CHANGE isReservering isReservering TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE tw_huurovereenkomsten RENAME INDEX idx_453ff4a6ecdad1a9 TO IDX_98F99DF6ECDAD1A9');
        $this->addSql('ALTER TABLE tw_huurovereenkomsten RENAME INDEX uniq_453ff4a6656e2280 TO UNIQ_98F99DF6656E2280');
        $this->addSql('ALTER TABLE tw_huurovereenkomsten RENAME INDEX uniq_453ff4a645da3bb7 TO UNIQ_98F99DF645DA3BB7');
        $this->addSql('ALTER TABLE tw_huurovereenkomsten RENAME INDEX idx_453ff4a63d707f64 TO IDX_98F99DF63D707F64');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_verslag DROP FOREIGN KEY FK_114A2160870B85BC');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_verslag DROP FOREIGN KEY FK_114A2160D949475D');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_verslag ADD CONSTRAINT FK_5F912B12870B85BC FOREIGN KEY (huurovereenkomst_id) REFERENCES tw_huurovereenkomsten (id)');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_verslag ADD CONSTRAINT FK_5F912B12D949475D FOREIGN KEY (verslag_id) REFERENCES tw_superverslagen (id)');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_verslag RENAME INDEX idx_114a2160870b85bc TO IDX_5F912B12870B85BC');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_verslag RENAME INDEX idx_114a2160d949475d TO IDX_5F912B12D949475D');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_finverslag ADD CONSTRAINT FK_98E469DC870B85BC FOREIGN KEY (huurovereenkomst_id) REFERENCES tw_huurovereenkomsten (id)');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_finverslag ADD CONSTRAINT FK_98E469DCD949475D FOREIGN KEY (verslag_id) REFERENCES tw_superverslagen (id)');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_finverslag RENAME INDEX idx_114a2160870b85bc TO IDX_98E469DC870B85BC');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_finverslag RENAME INDEX idx_114a2160d949475d TO IDX_98E469DCD949475D');

        $this->addSql('ALTER TABLE tw_huurovereenkomst_document ADD CONSTRAINT FK_C5DF83BD870B85BC FOREIGN KEY (huurovereenkomst_id) REFERENCES tw_huurovereenkomsten (id)');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_document ADD CONSTRAINT FK_C5DF83BDC33F7837 FOREIGN KEY (document_id) REFERENCES tw_superdocumenten (id)');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_document RENAME INDEX idx_7b9a48a7870b85bc TO IDX_C5DF83BD870B85BC');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_document RENAME INDEX idx_7b9a48a7c33f7837 TO IDX_C5DF83BDC33F7837');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_findocument ADD CONSTRAINT FK_B9C41948870B85BC FOREIGN KEY (huurovereenkomst_id) REFERENCES tw_huurovereenkomsten (id)');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_findocument ADD CONSTRAINT FK_B9C41948C33F7837 FOREIGN KEY (document_id) REFERENCES tw_superdocumenten (id)');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_findocument RENAME INDEX idx_7b9a48a7870b85bc TO IDX_B9C41948870B85BC');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_findocument RENAME INDEX idx_7b9a48a7c33f7837 TO IDX_B9C41948C33F7837');
//        $this->addSql('ALTER TABLE tw_deelnames DROP FOREIGN KEY FK_B0B3FDE19D1883DD');
//        $this->addSql('DROP INDEX IDX_B0B3FDE19D1883DD ON tw_deelnames');
        $this->addSql('ALTER TABLE tw_deelnames CHANGE odp_vrijwilliger_id tw_vrijwilliger_id INT NOT NULL');
        $this->addSql('ALTER TABLE tw_deelnames ADD CONSTRAINT FK_C8B28A18629A95E FOREIGN KEY (tw_vrijwilliger_id) REFERENCES tw_vrijwilligers (id)');
        $this->addSql('CREATE INDEX IDX_C8B28A18629A95E ON tw_deelnames (tw_vrijwilliger_id)');
        $this->addSql('ALTER TABLE tw_deelnames RENAME INDEX idx_b0b3fde1459f3233 TO IDX_C8B28A18459F3233');
        $this->addSql('ALTER TABLE tw_vrijwilligers DROP startdatum, DROP medewerkerLocatie_id');
        $this->addSql('ALTER TABLE tw_vrijwilligers RENAME INDEX uniq_198b651476b43bdc TO UNIQ_F49E8AA376B43BDC');
        $this->addSql('ALTER TABLE tw_vrijwilligers RENAME INDEX idx_198b65144c676e6b TO IDX_F49E8AA34C676E6B');
        $this->addSql('ALTER TABLE tw_vrijwilligers RENAME INDEX idx_198b6514ca12f7ae TO IDX_F49E8AA3CA12F7AE');
        $this->addSql('ALTER TABLE tw_vrijwilligers RENAME INDEX idx_198b65143d707f64 TO IDX_F49E8AA33D707F64');
        $this->addSql('ALTER TABLE tw_vrijwilliger_locatie RENAME INDEX idx_2199949576b43bdc TO IDX_AF4CCDFB76B43BDC');
        $this->addSql('ALTER TABLE tw_vrijwilliger_locatie RENAME INDEX idx_219994954947630c TO IDX_AF4CCDFB4947630C');
        $this->addSql('ALTER TABLE tw_vrijwilliger_memo RENAME INDEX idx_8200726c76b43bdc TO IDX_4915EB0376B43BDC');
        $this->addSql('ALTER TABLE tw_vrijwilliger_memo RENAME INDEX uniq_8200726cb4d32439 TO UNIQ_4915EB03B4D32439');
        $this->addSql('ALTER TABLE tw_vrijwilliger_document RENAME INDEX idx_8454b6ba76b43bdc TO IDX_2ED02FBC76B43BDC');
        $this->addSql('ALTER TABLE tw_vrijwilliger_document RENAME INDEX uniq_8454b6bac33f7837 TO UNIQ_2ED02FBCC33F7837');
        $this->addSql('ALTER TABLE tw_intakes RENAME INDEX uniq_3a1e7f775dfa57a1 TO UNIQ_32F028325DFA57A1');
        $this->addSql('ALTER TABLE tw_intakes RENAME INDEX idx_3a1e7f773d707f64 TO IDX_32F028323D707F64');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tw_afsluitingen CHANGE tonen tonen TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE active active TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE tw_deelnames DROP FOREIGN KEY FK_C8B28A18629A95E');
        $this->addSql('DROP INDEX IDX_C8B28A18629A95E ON tw_deelnames');
        $this->addSql('ALTER TABLE tw_deelnames CHANGE tw_vrijwilliger_id odp_vrijwilliger_id INT NOT NULL');
        $this->addSql('ALTER TABLE tw_deelnames ADD CONSTRAINT FK_B0B3FDE19D1883DD FOREIGN KEY (odp_vrijwilliger_id) REFERENCES tw_vrijwilligers (id)');
        $this->addSql('CREATE INDEX IDX_B0B3FDE19D1883DD ON tw_deelnames (odp_vrijwilliger_id)');
        $this->addSql('ALTER TABLE tw_deelnames RENAME INDEX idx_c8b28a18459f3233 TO IDX_B0B3FDE1459F3233');
        $this->addSql('ALTER TABLE tw_deelnemer_document RENAME INDEX idx_466075955dfa57a1 TO IDX_9BA61CC55DFA57A1');
        $this->addSql('ALTER TABLE tw_deelnemer_document RENAME INDEX idx_46607595c33f7837 TO IDX_9BA61CC5C33F7837');
        $this->addSql('ALTER TABLE tw_deelnemer_verslag RENAME INDEX idx_33e2c4055dfa57a1 TO IDX_F8F75D6A5DFA57A1');
        $this->addSql('ALTER TABLE tw_deelnemer_verslag RENAME INDEX idx_33e2c405d949475d TO IDX_F8F75D6AD949475D');
        $this->addSql('ALTER TABLE tw_deelnemers DROP INDEX IDX_E43172562BB8C0FB, ADD UNIQUE INDEX UNIQ_3A1E7F772BB8C0FB (ambulantOndersteuner_id)');
        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E43172565C217849');
        $this->addSql('DROP INDEX IDX_E43172565C217849 ON tw_deelnemers');
        $this->addSql('ALTER TABLE tw_deelnemers CHANGE project_id project_id INT DEFAULT NULL, CHANGE appklant_id klant_id INT NOT NULL');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_202839993C427B2F FOREIGN KEY (appKlant_id) REFERENCES klanten (id)');
        $this->addSql('CREATE INDEX IDX_202839993C427B2F ON tw_deelnemers (appKlant_id)');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX idx_e43172564a06a057 TO FK_20283999C8F722F4');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX idx_e43172561aaf70bd TO IDX_202839991AAF70BD');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX idx_e4317256c8250f57 TO IDX_20283999C8250F57');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX idx_e43172563d707f64 TO IDX_202839993D707F64');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX idx_e4317256c0b11400 TO IDX_20283999C0B11400');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX idx_e4317256d93d6b19 TO IDX_20283999D93D6B19');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX idx_e4317256166d1f9c TO IDX_20283999166D1F9C');
        $this->addSql('ALTER TABLE tw_deelnemers RENAME INDEX idx_e4317256ecdad1a9 TO IDX_20283999ECDAD1A9');
        $this->addSql('ALTER TABLE tw_huuraanbiedingen CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tw_huuraanbiedingen RENAME INDEX idx_8e0aac373d707f64 TO IDX_FA204F873D707F64');
        $this->addSql('ALTER TABLE tw_huuraanbiedingen RENAME INDEX idx_8e0aac37b79d9579 TO IDX_FA204F87522CF24B');
        $this->addSql('ALTER TABLE tw_huuraanbiedingen RENAME INDEX idx_8e0aac377e18485d TO IDX_FA204F877E18485D');
        $this->addSql('ALTER TABLE tw_huuraanbiedingen RENAME INDEX idx_8e0aac37ecdad1a9 TO IDX_FA204F87ECDAD1A9');
        $this->addSql('ALTER TABLE tw_huuraanbiedingen RENAME INDEX idx_8e0aac37166d1f9c TO IDX_FA204F87166D1F9C');
        $this->addSql('ALTER TABLE tw_huuraanbod_verslag RENAME INDEX idx_46eb8e0b656e2280 TO IDX_9B2DE75B656E2280');
        $this->addSql('ALTER TABLE tw_huuraanbod_verslag RENAME INDEX idx_46eb8e0bd949475d TO IDX_9B2DE75BD949475D');
        $this->addSql('ALTER TABLE tw_huurders_tw_projecten RENAME INDEX idx_34e5855eb36f4ca5 TO IDX_48E405357776076A');
        $this->addSql('ALTER TABLE tw_huurders_tw_projecten RENAME INDEX idx_34e5855e3b4a66e3 TO IDX_48E40535FF532D2C');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_document DROP FOREIGN KEY FK_C5DF83BD870B85BC');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_document DROP FOREIGN KEY FK_C5DF83BDC33F7837');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_document ADD CONSTRAINT FK_7B9A48A7870B85BC FOREIGN KEY (huurovereenkomst_id) REFERENCES tw_huurovereenkomsten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_document ADD CONSTRAINT FK_7B9A48A7C33F7837 FOREIGN KEY (document_id) REFERENCES tw_superdocumenten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_document RENAME INDEX idx_c5df83bd870b85bc TO IDX_7B9A48A7870B85BC');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_document RENAME INDEX idx_c5df83bdc33f7837 TO IDX_7B9A48A7C33F7837');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_findocument DROP FOREIGN KEY FK_B9C41948870B85BC');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_findocument DROP FOREIGN KEY FK_B9C41948C33F7837');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_findocument RENAME INDEX idx_b9c41948870b85bc TO IDX_7B9A48A7870B85BC');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_findocument RENAME INDEX idx_b9c41948c33f7837 TO IDX_7B9A48A7C33F7837');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_finverslag DROP FOREIGN KEY FK_98E469DC870B85BC');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_finverslag DROP FOREIGN KEY FK_98E469DCD949475D');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_finverslag RENAME INDEX idx_98e469dc870b85bc TO IDX_114A2160870B85BC');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_finverslag RENAME INDEX idx_98e469dcd949475d TO IDX_114A2160D949475D');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_verslag DROP FOREIGN KEY FK_5F912B12870B85BC');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_verslag DROP FOREIGN KEY FK_5F912B12D949475D');
//        $this->addSql('ALTER TABLE tw_huurovereenkomst_verslag ADD CONSTRAINT FK_114A2160870B85BC FOREIGN KEY (huurovereenkomst_id) REFERENCES tw_huurovereenkomsten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_verslag ADD CONSTRAINT FK_114A2160D949475D FOREIGN KEY (verslag_id) REFERENCES tw_superverslagen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_verslag RENAME INDEX idx_5f912b12870b85bc TO IDX_114A2160870B85BC');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_verslag RENAME INDEX idx_5f912b12d949475d TO IDX_114A2160D949475D');
        $this->addSql('ALTER TABLE tw_huurovereenkomsten CHANGE opzegbrief_verstuurd opzegbrief_verstuurd TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE isReservering isReservering TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE tw_huurovereenkomsten RENAME INDEX uniq_98f99df645da3bb7 TO UNIQ_453FF4A645DA3BB7');
        $this->addSql('ALTER TABLE tw_huurovereenkomsten RENAME INDEX idx_98f99df6ecdad1a9 TO IDX_453FF4A6ECDAD1A9');
        $this->addSql('ALTER TABLE tw_huurovereenkomsten RENAME INDEX uniq_98f99df6656e2280 TO UNIQ_453FF4A6656E2280');
        $this->addSql('ALTER TABLE tw_huurovereenkomsten RENAME INDEX idx_98f99df63d707f64 TO IDX_453FF4A63D707F64');
        $this->addSql('ALTER TABLE tw_huurverzoek_verslag RENAME INDEX idx_2d7ddf5c45da3bb7 TO IDX_46CB48C145DA3BB7');
        $this->addSql('ALTER TABLE tw_huurverzoek_verslag RENAME INDEX idx_2d7ddf5cd949475d TO IDX_46CB48C1D949475D');
        $this->addSql('ALTER TABLE tw_huurverzoeken RENAME INDEX idx_b59aa1213d707f64 TO IDX_588F4E963D707F64');
        $this->addSql('ALTER TABLE tw_huurverzoeken RENAME INDEX idx_b59aa1219e4835da TO IDX_588F4E969E4835DA');
        $this->addSql('ALTER TABLE tw_huurverzoeken RENAME INDEX idx_b59aa121ecdad1a9 TO IDX_588F4E96ECDAD1A9');
        $this->addSql('ALTER TABLE tw_huurverzoeken_tw_projecten RENAME INDEX idx_1466f1bbb2bc36b2 TO IDX_CDF6EEBCE7540572');
        $this->addSql('ALTER TABLE tw_huurverzoeken_tw_projecten RENAME INDEX idx_1466f1bb3b4a66e3 TO IDX_CDF6EEBCFF532D2C');
        $this->addSql('ALTER TABLE tw_intakes RENAME INDEX idx_32f028323d707f64 TO IDX_3A1E7F773D707F64');
        $this->addSql('ALTER TABLE tw_intakes RENAME INDEX uniq_32f028325dfa57a1 TO UNIQ_3A1E7F775DFA57A1');
        $this->addSql('ALTER TABLE tw_superdocumenten CHANGE class class VARCHAR(15) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE tw_superdocumenten RENAME INDEX idx_1633b5253d707f64 TO IDX_6E6F9FD53D707F64');
        $this->addSql('ALTER TABLE tw_superverslagen CHANGE class class VARCHAR(15) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE tw_superverslagen RENAME INDEX idx_2dce6d3f3d707f64 TO IDX_762D3F773D707F64');
        $this->addSql('ALTER TABLE tw_vrijwilliger_document RENAME INDEX uniq_2ed02fbcc33f7837 TO UNIQ_8454B6BAC33F7837');
        $this->addSql('ALTER TABLE tw_vrijwilliger_document RENAME INDEX idx_2ed02fbc76b43bdc TO IDX_8454B6BA76B43BDC');
        $this->addSql('ALTER TABLE tw_vrijwilliger_locatie RENAME INDEX idx_af4ccdfb76b43bdc TO IDX_2199949576B43BDC');
        $this->addSql('ALTER TABLE tw_vrijwilliger_locatie RENAME INDEX idx_af4ccdfb4947630c TO IDX_219994954947630C');
        $this->addSql('ALTER TABLE tw_vrijwilliger_memo RENAME INDEX uniq_4915eb03b4d32439 TO UNIQ_8200726CB4D32439');
        $this->addSql('ALTER TABLE tw_vrijwilliger_memo RENAME INDEX idx_4915eb0376b43bdc TO IDX_8200726C76B43BDC');
        $this->addSql('ALTER TABLE tw_vrijwilligers ADD startdatum DATE DEFAULT NULL, ADD medewerkerLocatie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tw_vrijwilligers RENAME INDEX idx_f49e8aa34c676e6b TO IDX_198B65144C676E6B');
        $this->addSql('ALTER TABLE tw_vrijwilligers RENAME INDEX idx_f49e8aa33d707f64 TO IDX_198B65143D707F64');
        $this->addSql('ALTER TABLE tw_vrijwilligers RENAME INDEX uniq_f49e8aa376b43bdc TO UNIQ_198B651476B43BDC');
        $this->addSql('ALTER TABLE tw_vrijwilligers RENAME INDEX idx_f49e8aa3ca12f7ae TO IDX_198B6514CA12F7AE');


    }
}
