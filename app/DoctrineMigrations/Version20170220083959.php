<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170220083959 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE oek_deelnames (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, oekTraining_id INT NOT NULL, oekKlant_id INT NOT NULL, INDEX IDX_A6C1F201120845B9 (oekTraining_id), INDEX IDX_A6C1F201E145C54F (oekKlant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oek_lidmaatschappen (id INT AUTO_INCREMENT NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, oekGroep_id INT NOT NULL, oekKlant_id INT NOT NULL, INDEX IDX_7B0B7DFF43B3F0A5 (oekGroep_id), INDEX IDX_7B0B7DFFE145C54F (oekKlant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE oek_deelnames ADD CONSTRAINT FK_A6C1F201120845B9 FOREIGN KEY (oekTraining_id) REFERENCES oek_trainingen (id)');
        $this->addSql('ALTER TABLE oek_deelnames ADD CONSTRAINT FK_A6C1F201E145C54F FOREIGN KEY (oekKlant_id) REFERENCES oek_klanten (id)');
        $this->addSql('ALTER TABLE oek_lidmaatschappen ADD CONSTRAINT FK_7B0B7DFF43B3F0A5 FOREIGN KEY (oekGroep_id) REFERENCES oek_groepen (id)');
        $this->addSql('ALTER TABLE oek_lidmaatschappen ADD CONSTRAINT FK_7B0B7DFFE145C54F FOREIGN KEY (oekKlant_id) REFERENCES oek_klanten (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
