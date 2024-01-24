<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200706110934 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_deelnemers ADD ambulantOndersteuner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_deelnemers ADD CONSTRAINT FK_3A1E7F772BB8C0FB FOREIGN KEY (ambulantOndersteuner_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3A1E7F772BB8C0FB ON odp_deelnemers (ambulantOndersteuner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_deelnemers DROP FOREIGN KEY FK_3A1E7F772BB8C0FB');
        $this->addSql('DROP INDEX UNIQ_3A1E7F772BB8C0FB ON odp_deelnemers');
        $this->addSql('ALTER TABLE odp_deelnemers DROP ambulantOndersteuner_id');
    }
}
