<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190128151531 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE locaties CHANGE datum_tot datum_tot DATE DEFAULT NULL');
        $this->addSql("UPDATE locaties SET datum_van = '2010-01-01' WHERE datum_van = '0000-00-00'");
        $this->addSql("UPDATE locaties SET datum_tot = NULL WHERE datum_tot = '0000-00-00'");
    }

    public function down(Schema $schema) : void
    {
        $this->throwIrreversibleMigrationException();
    }
}
