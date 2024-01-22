<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180824084127 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE groepsactiviteiten_intakes CHANGE medewerker_id medewerker_id INT DEFAULT NULL');
        $this->addSql('UPDATE groepsactiviteiten_intakes SET medewerker_id = NULL WHERE medewerker_id = 0');
        $this->addSql('ALTER TABLE groepsactiviteiten_intakes ADD CONSTRAINT FK_843277B3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_843277B3D707F64 ON groepsactiviteiten_intakes (medewerker_id)');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
