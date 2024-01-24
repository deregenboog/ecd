<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230712101902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Prepare moving Dagbesteding verslagen and documenten from trajecten to deelnemer';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("START TRANSACTION");
        $this->addSql('ALTER TABLE dagbesteding_deelnemer_document DROP FOREIGN KEY FK_89539E645DFA57A1');
        $this->addSql('ALTER TABLE dagbesteding_deelnemer_document DROP FOREIGN KEY FK_89539E64C33F7837');
        $this->addSql('ALTER TABLE dagbesteding_deelnemer_verslag DROP FOREIGN KEY FK_BA9CAC335DFA57A1');
        $this->addSql('ALTER TABLE dagbesteding_deelnemer_verslag DROP FOREIGN KEY FK_BA9CAC33D949475D');
        $this->addSql('ALTER TABLE dagbesteding_traject_document DROP FOREIGN KEY FK_5408B1ADA0CADD4');
        $this->addSql('ALTER TABLE dagbesteding_traject_document DROP FOREIGN KEY FK_5408B1ADC33F7837');
        $this->addSql('ALTER TABLE dagbesteding_traject_verslag DROP FOREIGN KEY FK_ECCFAC5CA0CADD4');
        $this->addSql('ALTER TABLE dagbesteding_traject_verslag DROP FOREIGN KEY FK_ECCFAC5CD949475D');



        $this->addSql('ALTER TABLE dagbesteding_deelnames RENAME INDEX idx_328ad7035dfa57a1 TO IDX_328AD703A0CADD4');
        $this->addSql('ALTER TABLE dagbesteding_documenten ADD deelnemer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dagbesteding_documenten ADD traject_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dagbesteding_documenten ADD CONSTRAINT FK_20E925AB5DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES dagbesteding_deelnemers (id)');
        $this->addSql('CREATE INDEX IDX_20E925AB5DFA57A1 ON dagbesteding_documenten (deelnemer_id)');
        $this->addSql('ALTER TABLE dagbesteding_verslagen ADD deelnemer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dagbesteding_verslagen ADD traject_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dagbesteding_verslagen ADD CONSTRAINT FK_F41415955DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES dagbesteding_deelnemers (id)');
        $this->addSql('CREATE INDEX IDX_F41415955DFA57A1 ON dagbesteding_verslagen (deelnemer_id)');

        $this->addSql("COMMIT");


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("START TRANSACTION");


        $this->addSql('ALTER TABLE dagbesteding_deelnames RENAME INDEX idx_328ad703a0cadd4 TO IDX_328AD7035DFA57A1');
        $this->addSql('ALTER TABLE dagbesteding_documenten DROP FOREIGN KEY FK_20E925AB5DFA57A1');
        $this->addSql('DROP INDEX IDX_20E925AB5DFA57A1 ON dagbesteding_documenten');
        $this->addSql('ALTER TABLE dagbesteding_documenten DROP deelnemer_id');
        $this->addSql('ALTER TABLE dagbesteding_documenten DROP traject_id');
        $this->addSql('ALTER TABLE dagbesteding_verslagen DROP FOREIGN KEY FK_F41415955DFA57A1');
        $this->addSql('DROP INDEX IDX_F41415955DFA57A1 ON dagbesteding_verslagen');
        $this->addSql('ALTER TABLE dagbesteding_verslagen DROP deelnemer_id');
        $this->addSql('ALTER TABLE dagbesteding_verslagen DROP traject_id');

        $this->addSql("COMMIT");

    }
}
