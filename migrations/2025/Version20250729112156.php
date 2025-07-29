<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250729112156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE iz_koppelingen ADD maatje_minder_fysiek TINYINT(1) DEFAULT NULL, ADD maatje_minder_frequent TINYINT(1) DEFAULT NULL, ADD maatje_minder_lang TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE iz_koppelingen DROP maatje_minder_fysiek, DROP maatje_minder_frequent, DROP maatje_minder_lang');
    }
}
