<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215092017 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add table klant_taal.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE klant_taal (id INT AUTO_INCREMENT NOT NULL, klant_id INT NOT NULL, taal_id INT NOT NULL, voorkeur TINYINT(1) NOT NULL, INDEX IDX_84884583C427B2F (klant_id), INDEX IDX_8488458A41FDDD (taal_id), UNIQUE INDEX UNIQ_84884583C427B2FA41FDDD (klant_id, taal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE klant_taal ADD CONSTRAINT FK_84884583C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE klant_taal ADD CONSTRAINT FK_8488458A41FDDD FOREIGN KEY (taal_id) REFERENCES talen (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE klant_taal');
    }
}
