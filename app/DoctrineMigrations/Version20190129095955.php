<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190129095955 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql("UPDATE locatie_tijden SET openingstijd = '20:00:00' WHERE openingstijd = '-04:00:00'");

    }

    public function down(Schema $schema) : void
    {
        $this->throwIrreversibleMigrationException();
    }
}
