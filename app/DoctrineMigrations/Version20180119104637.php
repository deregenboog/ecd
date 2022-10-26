<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180119104637 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hs_klus_activiteit (klus_id INT NOT NULL, activiteit_id INT NOT NULL, INDEX IDX_AE073F88BA5374AF (klus_id), INDEX IDX_AE073F885A8A0A1 (activiteit_id), PRIMARY KEY(klus_id, activiteit_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hs_klus_activiteit ADD CONSTRAINT FK_AE073F88BA5374AF FOREIGN KEY (klus_id) REFERENCES hs_klussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hs_klus_activiteit ADD CONSTRAINT FK_AE073F885A8A0A1 FOREIGN KEY (activiteit_id) REFERENCES hs_activiteiten (id) ON DELETE CASCADE');

        $this->addSql('INSERT INTO hs_klus_activiteit (klus_id, activiteit_id) SELECT id, activiteit_id FROM hs_klussen');

        $this->addSql('ALTER TABLE hs_klussen DROP FOREIGN KEY FK_3E9A80CF5A8A0A1');
        $this->addSql('DROP INDEX IDX_3E9A80CF5A8A0A1 ON hs_klussen');
        $this->addSql('ALTER TABLE hs_klussen DROP activiteit_id');
    }

    /**
     * @param Schema $schema
     */
     public function down(Schema $schema): void
    {
    }
}
