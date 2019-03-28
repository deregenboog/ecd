<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190328114627 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX idz_iz_verslag_iz_koppeling_id ON iz_verslagen');
        $this->addSql("ALTER TABLE iz_verslagen ADD discr VARCHAR(15) NOT NULL DEFAULT 'verslag'");
        $this->addSql('ALTER TABLE iz_verslagen CHANGE discr discr VARCHAR(15) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->throwIrreversibleMigrationException();
    }
}
