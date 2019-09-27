<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use IzBundle\Entity\Project;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171026175507 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE iz_doelstellingen (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, jaar INT NOT NULL, aantal INT NOT NULL, INDEX IDX_D76C6C73166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iz_doelstellingen ADD CONSTRAINT FK_D76C6C73166D1F9C FOREIGN KEY (project_id) REFERENCES iz_projecten (id)');
        $this->addSql('CREATE UNIQUE INDEX unique_project_jaar_idx ON iz_doelstellingen (project_id, jaar)');
        $this->addSql('ALTER TABLE iz_projecten ADD prestatie_strategy VARCHAR(255) NOT NULL');

        $this->addSql(sprintf(
            "UPDATE iz_projecten SET prestatie_strategy = '%s'",
            Project::STRATEGY_PRESTATIE_TOTAL
        ));
        $this->addSql(sprintf(
            "UPDATE iz_projecten SET prestatie_strategy = '%s' WHERE naam IN ('PM - maatje', 'TC - coach', 'VONK - maatje')",
            Project::STRATEGY_PRESTATIE_STARTED
        ));
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
