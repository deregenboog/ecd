<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190219142847 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE inloop_toegang (klant_id INT NOT NULL, locatie_id INT NOT NULL, INDEX IDX_C2038C803C427B2F (klant_id), INDEX IDX_C2038C804947630C (locatie_id), PRIMARY KEY(klant_id, locatie_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inloop_toegang ADD CONSTRAINT FK_C2038C803C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE inloop_toegang ADD CONSTRAINT FK_C2038C804947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id)');
    }

    public function down(Schema $schema) : void
    {
        $this->throwIrreversibleMigrationException();
    }
}
