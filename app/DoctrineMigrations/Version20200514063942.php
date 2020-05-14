<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200514063942 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE doelstellingen DROP FOREIGN KEY FK_2CAF1940A13D3FD8');
        $this->addSql('DROP INDEX IDX_2CAF1940A13D3FD8 ON doelstellingen');
        $this->addSql('ALTER TABLE doelstellingen ADD label VARCHAR(255) DEFAULT NULL, ADD kostenplaats VARCHAR(255) DEFAULT NULL, DROP stadsdeel, DROP kpi, DROP categorie');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE doelstellingen ADD stadsdeel VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, ADD kpi VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, ADD categorie VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, DROP label, DROP kostenplaats');
        $this->addSql('ALTER TABLE doelstellingen ADD CONSTRAINT FK_2CAF1940A13D3FD8 FOREIGN KEY (stadsdeel) REFERENCES werkgebieden (naam)');
        $this->addSql('CREATE INDEX IDX_2CAF1940A13D3FD8 ON doelstellingen (stadsdeel)');

    }
}
