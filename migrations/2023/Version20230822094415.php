<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822094415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding contactmomenten to oekraine verslagen';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE oekraine_verslagen ADD aantalContactmomenten INT NOT NULL');
        $this->addSql('UPDATE oekraine_verslagen SET aantalContactmomenten = 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE oekraine_verslagen DROP aantalContactmomenten');

    }
}
