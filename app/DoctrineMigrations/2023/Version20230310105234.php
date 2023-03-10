<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310105234 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("ALTER TABLE inloop_vrijwilligers DROP FOREIGN KEY FK_561104804947630C");
        $this->addSql('ALTER TABLE inloop_vrijwilligers DROP locatie_id, CHANGE created created DATETIME NOT NULL, CHANGE modified modified DATETIME NOT NULL');
//
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE inloop_vrijwilligers ADD locatie_id INT DEFAULT NULL, CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE modified modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');


    }
}
