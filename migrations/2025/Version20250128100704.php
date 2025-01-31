<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128100704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change OpenHuis to Buurtrestaurants';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE ga_groepen SET discr = 'Buurtrestaurants' WHERE discr = 'OpenHuis'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("UPDATE ga_groepen SET discr = 'OpenHuis' WHERE discr = 'Buurtrestaurants'");
    }
}
