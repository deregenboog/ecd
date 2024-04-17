<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240417104410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Doelgroepen toevoegen aan afsluitingen';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE iz_afsluitingen ADD doelgroepen INT DEFAULT 3');
        $this->addSql('ALTER TABLE iz_afsluitingen MODIFY COLUMN doelgroepen INT NOT NULL;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE iz_afsluitingen DROP doelgroepen');
    }
}
