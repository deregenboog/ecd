<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180110102918 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hs_klus_document (klus_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_869EC9C5BA5374AF (klus_id), UNIQUE INDEX UNIQ_869EC9C5C33F7837 (document_id), PRIMARY KEY(klus_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hs_klus_document ADD CONSTRAINT FK_869EC9C5BA5374AF FOREIGN KEY (klus_id) REFERENCES hs_klussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_klus_document ADD CONSTRAINT FK_869EC9C5C33F7837 FOREIGN KEY (document_id) REFERENCES hs_documenten (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
