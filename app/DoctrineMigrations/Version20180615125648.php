<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180615125648 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AE48D0634A FOREIGN KEY (verblijfstatus_id) REFERENCES verblijfstatussen (id)');
        $this->addSql('DROP INDEX idx_intakes_verblijfstatus_id ON intakes');
        $this->addSql('CREATE INDEX IDX_AB70F5AE48D0634A ON intakes (verblijfstatus_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
