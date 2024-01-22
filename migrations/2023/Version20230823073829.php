<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230823073829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename Villa Zaanstad to Villa Westerweide';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE locaties SET naam = 'Villa Westerweide' WHERE naam = 'Villa Zaanstad'");
        $this->addSql("UPDATE locaties SET naam = 'Villa Westerweide gebruikersruimte' WHERE naam = 'Villa Zaanstad gebruikersruimte'");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE locaties SET naam = 'Villa Zaanstad' WHERE naam = 'Villa Westerweide'");
        $this->addSql("UPDATE locaties SET naam = 'Villa Zaanstad gebruikersruimte' WHERE naam = 'Villa Westerweide gebruikersruimte'");

    }
}
