<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510111742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds delen columns too MW and TW verslagen.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE tw_superverslagen ADD delenMw TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE verslagen ADD delenTw TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE tw_superverslagen DROP delenMw');
        $this->addSql('ALTER TABLE verslagen DROP delenTw');

    }
}
