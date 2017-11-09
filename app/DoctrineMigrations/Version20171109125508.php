<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use IzBundle\Entity\Doelstelling;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171109125508 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE iz_doelstellingen ADD categorie VARCHAR(255) DEFAULT NULL');
        $this->addSql("UPDATE iz_doelstellingen SET categorie = NULL WHERE stadsdeel IS NOT NULL");
        $this->addSql("UPDATE iz_doelstellingen SET categorie = '".Doelstelling::CATEGORIE_CENTRALE_STAD."' WHERE stadsdeel IS NULL");
        $this->addSql('CREATE UNIQUE INDEX unique_project_jaar_categorie_idx ON iz_doelstellingen (project_id, jaar, categorie)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
