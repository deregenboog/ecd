<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180913143950 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE intakes SET verblijfstatus_id = NULL WHERE verblijfstatus_id = 0');
        $this->addSql('INSERT INTO verblijfstatussen (id) VALUES (8)');

        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AE48D0634A FOREIGN KEY (verblijfstatus_id) REFERENCES verblijfstatussen (id)');
        $this->addSql('CREATE INDEX IDX_AB70F5AE48D0634A ON intakes (verblijfstatus_id)');
        $this->addSql('DROP INDEX idx_intakes_verblijfstatus_id ON intakes');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
