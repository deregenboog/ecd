<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190916093536 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_documenten ADD class VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE odp_verslagen ADD class VARCHAR(15) NOT NULL');
        $this->addSql('UPDATE odb_documenten SET class = "document"');
        $this->addSql('UPDATE odb_verslagen SET class = "verslag"');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_documenten DROP class');
        $this->addSql('ALTER TABLE odp_verslagen DROP class');

    }
}
