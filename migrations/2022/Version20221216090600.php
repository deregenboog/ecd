<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221216090600 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE oekraine_incidenten DROP FOREIGN KEY FK_18404EA03C427B2F');
        $this->addSql('DROP INDEX IDX_18404EA03C427B2F ON oekraine_incidenten');
        $this->addSql('ALTER TABLE oekraine_incidenten DROP klant_id');
        $this->addSql('ALTER TABLE oekraine_incidenten ADD bezoeker_id INT NOT NULL');
        $this->addSql('ALTER TABLE oekraine_incidenten ADD CONSTRAINT FK_18404EA08AEEBAAE FOREIGN KEY (bezoeker_id) REFERENCES oekraine_bezoekers (id)');
        $this->addSql('CREATE INDEX IDX_18404EA08AEEBAAE ON oekraine_incidenten (bezoeker_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE oekraine_incidenten DROP FOREIGN KEY FK_18404EA08AEEBAAE');
        $this->addSql('DROP INDEX IDX_18404EA08AEEBAAE ON oekraine_incidenten');
        $this->addSql('ALTER TABLE oekraine_incidenten DROP bezoeker_id');
        $this->addSql('ALTER TABLE oekraine_incidenten ADD klant_id INT NOT NULL');
        $this->addSql('ALTER TABLE oekraine_incidenten ADD CONSTRAINT FK_18404EA03C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('CREATE INDEX IDX_18404EA03C427B2F ON oekraine_incidenten (klant_id)');
    }
}
