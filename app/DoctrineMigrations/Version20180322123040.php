<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180322123040 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE iz_hulpvraag_hulpvraagsoort');
        $this->addSql('ALTER TABLE iz_koppelingen DROP FOREIGN KEY FK_24E5FDC222638DF1');
        $this->addSql('DROP INDEX IDX_24E5FDC222638DF1 ON iz_koppelingen');
        $this->addSql('ALTER TABLE iz_koppelingen CHANGE primairehulpvraagsoort_id hulpvraagsoort_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE iz_koppelingen ADD CONSTRAINT FK_24E5FDC2950213F FOREIGN KEY (hulpvraagsoort_id) REFERENCES iz_hulpvraagsoorten (id)');
        $this->addSql('CREATE INDEX IDX_24E5FDC2950213F ON iz_koppelingen (hulpvraagsoort_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
