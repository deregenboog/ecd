<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221213141832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add table oekraine_bezoeker_document';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE oekraine_bezoeker_document (bezoeker_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_DEE5EC468AEEBAAE (bezoeker_id), UNIQUE INDEX UNIQ_DEE5EC46C33F7837 (document_id), PRIMARY KEY(bezoeker_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE oekraine_bezoeker_document ADD CONSTRAINT FK_DEE5EC468AEEBAAE FOREIGN KEY (bezoeker_id) REFERENCES oekraine_bezoekers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oekraine_bezoeker_document ADD CONSTRAINT FK_DEE5EC46C33F7837 FOREIGN KEY (document_id) REFERENCES oekraine_documenten (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE oekraine_bezoeker_document');
    }
}
