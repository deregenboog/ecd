<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220713144250 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE oekraine_intakes ADD intakelocatie_id INT DEFAULT NULL, DROP mag_gebruiken');
        $this->addSql('ALTER TABLE oekraine_intakes ADD CONSTRAINT FK_C84A94C355E45319 FOREIGN KEY (intakelocatie_id) REFERENCES oekraine_locaties (id)');
        $this->addSql('CREATE INDEX IDX_C84A94C355E45319 ON oekraine_intakes (intakelocatie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE oekraine_intakes DROP FOREIGN KEY FK_C84A94C355E45319');
        $this->addSql('DROP INDEX IDX_C84A94C355E45319 ON oekraine_intakes');
        $this->addSql('ALTER TABLE oekraine_intakes ADD mag_gebruiken TINYINT(1) DEFAULT NULL, DROP intakelocatie_id');
    }
}
