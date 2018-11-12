<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181112132039 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP VIEW registraties_recent');
        $this->addSql('CREATE TABLE registraties_recent (registratie_id INT NOT NULL, locatie_id INT DEFAULT NULL, klant_id INT DEFAULT NULL, max_buiten DATETIME NOT NULL, INDEX IDX_B1AD39F04947630C (locatie_id), INDEX IDX_B1AD39F03C427B2F (klant_id), PRIMARY KEY(registratie_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE registraties_recent ADD CONSTRAINT FK_B1AD39F05CD9765E FOREIGN KEY (registratie_id) REFERENCES registraties (id)');
        $this->addSql('ALTER TABLE registraties_recent ADD CONSTRAINT FK_B1AD39F04947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE registraties_recent ADD CONSTRAINT FK_B1AD39F03C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
