<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231011133237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add activatable trait to hs activiteiten.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE hs_activiteiten ADD `active` TINYINT(1) NOT NULL DEFAULT 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hs_activiteiten DROP `active`');
    }
}
