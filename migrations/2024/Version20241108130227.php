<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241108130227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add kleur column to iz_project, stagiair to hulp.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE iz_projecten ADD kleur VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE iz_koppelingen ADD stagiair TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE iz_projecten DROP kleur');
        $this->addSql('ALTER TABLE iz_koppelingen DROP stagiair');
    }
}
