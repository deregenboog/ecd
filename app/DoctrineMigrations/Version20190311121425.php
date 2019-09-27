<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190311121425 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE klanten CHANGE medewerker_id medewerker_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vrijwilligers CHANGE medewerker_id medewerker_id INT DEFAULT NULL');

        $this->addSql('UPDATE klanten SET medewerker_id = NULL WHERE medewerker_id  = 20655');
        $this->addSql('UPDATE vrijwilligers SET medewerker_id = NULL WHERE medewerker_id  = 20655');
    }

    public function down(Schema $schema) : void
    {
    }
}
