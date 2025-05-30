<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200114073433 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE inloop_vrijwilligers DROP FOREIGN KEY FK_56110480EA9C84FE');
        $this->addSql('ALTER TABLE inloop_vrijwilligers ADD CONSTRAINT FK_56110480EA9C84FE FOREIGN KEY (medewerkerLocatie_id) REFERENCES medewerkers (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE inloop_vrijwilligers DROP FOREIGN KEY FK_56110480EA9C84FE');
        $this->addSql('ALTER TABLE inloop_vrijwilligers ADD CONSTRAINT FK_56110480EA9C84FE FOREIGN KEY (medewerkerLocatie_id) REFERENCES locaties (id)');
    }
}
