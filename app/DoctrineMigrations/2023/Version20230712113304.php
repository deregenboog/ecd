<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230712113304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("ALTER TABLE dagbesteding_documenten ADD type VARCHAR(255) NOT NULL DEFAULT 'Divers' ");


    }

    public function down(Schema $schema): void
    {

        $this->addSql("ALTER TABLE dagbesteding_documenten DROP type");


    }
}
