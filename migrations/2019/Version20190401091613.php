<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190401091613 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE app_klant_document (klant_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_7BA5F5B3C427B2F (klant_id), UNIQUE INDEX UNIQ_7BA5F5BC33F7837 (document_id), PRIMARY KEY(klant_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_klant_document ADD CONSTRAINT FK_7BA5F5B3C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_klant_document ADD CONSTRAINT FK_7BA5F5BC33F7837 FOREIGN KEY (document_id) REFERENCES documenten (id)');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
