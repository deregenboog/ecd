<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190212133944 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE inloop_afsluitredenen_vrijwilligers (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inloop_vrijwilligers ADD afsluitreden_id INT DEFAULT NULL, ADD afsluitdatum DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE inloop_vrijwilligers ADD CONSTRAINT FK_56110480CA12F7AE FOREIGN KEY (afsluitreden_id) REFERENCES inloop_afsluitredenen_vrijwilligers (id)');
        $this->addSql('CREATE INDEX IDX_56110480CA12F7AE ON inloop_vrijwilligers (afsluitreden_id)');
    }

    public function down(Schema $schema) : void
    {
        $this->throwIrreversibleMigrationException();
    }
}
