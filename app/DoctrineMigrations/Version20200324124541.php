<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200324124541 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('CREATE TABLE doelstellingen (id INT AUTO_INCREMENT NOT NULL, repository VARCHAR(255) DEFAULT NULL, stadsdeel VARCHAR(255) DEFAULT NULL, kpi VARCHAR(255) NOT NULL, jaar INT NOT NULL, categorie VARCHAR(255) DEFAULT NULL, aantal INT NOT NULL, INDEX IDX_2CAF1940A13D3FD8 (stadsdeel), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE doelstellingen ADD CONSTRAINT FK_2CAF1940A13D3FD8 FOREIGN KEY (stadsdeel) REFERENCES werkgebieden (naam)');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE doelstellingen');
    }
}
