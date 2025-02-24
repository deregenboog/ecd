<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250204134730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add active field to oek_groepen';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE oek_groepen ADD active TINYINT(1) NOT NULL DEFAULT 1');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE oek_groepen DROP active');
    }
}
