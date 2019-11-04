<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191104105518 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE vrijwilligers ADD geinformeerd_opslaan_gegevens TINYINT(1) NOT NULL');

    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE vrijwilligers DROP geinformeerd_opslaan_gegevens ');

    }
}
