<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170116144631 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE oek_groepen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oekgroep_oekklant (oekgroep_id INT NOT NULL, oekklant_id INT NOT NULL, INDEX IDX_78627AC0BAC592F3 (oekgroep_id), INDEX IDX_78627AC01833A719 (oekklant_id), PRIMARY KEY(oekgroep_id, oekklant_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oek_klanten (id INT AUTO_INCREMENT NOT NULL, klant_id INT NOT NULL, aanmelding DATE NOT NULL, verwijzing_door VARCHAR(255) NOT NULL, afsluiting DATE NOT NULL, verwijzing_naar VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, UNIQUE INDEX UNIQ_A501F8F73C427B2F (klant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oek_trainingen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, startDatum DATETIME NOT NULL, startTijd TIME NOT NULL, eindDatum DATE NOT NULL, locatie VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, oekGroep_id INT DEFAULT NULL, INDEX IDX_B0D582D43B3F0A5 (oekGroep_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oektraining_oekklant (oektraining_id INT NOT NULL, oekklant_id INT NOT NULL, INDEX IDX_909BF87A9494CA6E (oektraining_id), INDEX IDX_909BF87A1833A719 (oekklant_id), PRIMARY KEY(oektraining_id, oekklant_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');

        $this->addSql('ALTER TABLE oekgroep_oekklant ADD CONSTRAINT FK_78627AC0BAC592F3 FOREIGN KEY (oekgroep_id) REFERENCES oek_groepen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oekgroep_oekklant ADD CONSTRAINT FK_78627AC01833A719 FOREIGN KEY (oekklant_id) REFERENCES oek_klanten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oek_klanten ADD CONSTRAINT FK_A501F8F73C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE oek_trainingen ADD CONSTRAINT FK_B0D582D43B3F0A5 FOREIGN KEY (oekGroep_id) REFERENCES oek_groepen (id)');
        $this->addSql('ALTER TABLE oektraining_oekklant ADD CONSTRAINT FK_909BF87A9494CA6E FOREIGN KEY (oektraining_id) REFERENCES oek_trainingen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oektraining_oekklant ADD CONSTRAINT FK_909BF87A1833A719 FOREIGN KEY (oekklant_id) REFERENCES oek_klanten (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE oekgroep_oekklant DROP FOREIGN KEY FK_78627AC0BAC592F3');
        $this->addSql('ALTER TABLE oek_trainingen DROP FOREIGN KEY FK_B0D582D43B3F0A5');
        $this->addSql('ALTER TABLE oekgroep_oekklant DROP FOREIGN KEY FK_78627AC01833A719');
        $this->addSql('ALTER TABLE oektraining_oekklant DROP FOREIGN KEY FK_909BF87A1833A719');
        $this->addSql('ALTER TABLE oektraining_oekklant DROP FOREIGN KEY FK_909BF87A9494CA6E');

        $this->addSql('DROP TABLE oek_groepen');
        $this->addSql('DROP TABLE oekgroep_oekklant');
        $this->addSql('DROP TABLE oek_klanten');
        $this->addSql('DROP TABLE oek_trainingen');
        $this->addSql('DROP TABLE oektraining_oekklant');
    }
}
