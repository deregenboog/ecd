<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220324115350 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE inloop_incidenten (id INT AUTO_INCREMENT NOT NULL, locatie_id INT DEFAULT NULL, klant_id INT NOT NULL, datum DATE NOT NULL, remark LONGTEXT DEFAULT NULL, politie TINYINT(1) NOT NULL DEFAULT 0, ambulance TINYINT(1) NOT NULL DEFAULT 0, crisisdienst TINYINT(1) NOT NULL DEFAULT 0, created DATETIME NOT NULL DEFAULT NOW(), modified DATETIME NOT NULL DEFAULT NOW(), INDEX IDX_F85DD4754947630C (locatie_id), INDEX IDX_F85DD4753C427B2F (klant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inloop_incidenten ADD CONSTRAINT FK_F85DD4754947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE inloop_incidenten ADD CONSTRAINT FK_F85DD4753C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');

    }

     public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE inloop_incidenten');

    }
}
