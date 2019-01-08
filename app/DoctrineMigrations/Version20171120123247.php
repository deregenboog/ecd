<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171120123247 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clip_clienten DROP FOREIGN KEY FK_B7F4C67E3C427B2F');
        $this->addSql('DROP INDEX UNIQ_B7F4C67E3C427B2F ON clip_clienten');
        $this->addSql('ALTER TABLE clip_clienten ADD geslacht_id INT DEFAULT NULL, ADD werkgebied VARCHAR(255) DEFAULT NULL, ADD postcodegebied VARCHAR(255) DEFAULT NULL, ADD etniciteit VARCHAR(255) DEFAULT NULL, ADD geboortedatum DATE DEFAULT NULL, ADD voornaam VARCHAR(255) DEFAULT NULL, ADD roepnaam VARCHAR(255) DEFAULT NULL, ADD tussenvoegsel VARCHAR(255) DEFAULT NULL, ADD achternaam VARCHAR(255) DEFAULT NULL, ADD adres VARCHAR(255) DEFAULT NULL, ADD postcode VARCHAR(255) DEFAULT NULL, ADD plaats VARCHAR(255) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD mobiel VARCHAR(255) DEFAULT NULL, ADD telefoon VARCHAR(255) DEFAULT NULL, DROP klant_id, CHANGE viacategorie_id viacategorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE clip_clienten ADD CONSTRAINT FK_B7F4C67E1C729A47 FOREIGN KEY (geslacht_id) REFERENCES geslachten (id)');
        $this->addSql('ALTER TABLE clip_clienten ADD CONSTRAINT FK_B7F4C67E46708ED5 FOREIGN KEY (werkgebied) REFERENCES werkgebieden (naam)');
        $this->addSql('ALTER TABLE clip_clienten ADD CONSTRAINT FK_B7F4C67EFB02B9C2 FOREIGN KEY (postcodegebied) REFERENCES ggw_gebieden (naam)');
        $this->addSql('CREATE INDEX IDX_B7F4C67E1C729A47 ON clip_clienten (geslacht_id)');
        $this->addSql('CREATE INDEX IDX_B7F4C67E46708ED5 ON clip_clienten (werkgebied)');
        $this->addSql('CREATE INDEX IDX_B7F4C67EFB02B9C2 ON clip_clienten (postcodegebied)');
        $this->addSql('ALTER TABLE clip_vragen CHANGE hulpvrager_id hulpvrager_id INT DEFAULT NULL, CHANGE leeftijdscategorie_id leeftijdscategorie_id INT DEFAULT NULL, CHANGE communicatiekanaal_id communicatiekanaal_id INT DEFAULT NULL, CHANGE omschrijving omschrijving VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
