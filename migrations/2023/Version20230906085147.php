<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230906085147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE oekraine_verslagen DROP FOREIGN KEY FK_C15C9D6FD3899023');
        $this->addSql('DROP INDEX IDX_C15C9D6FD3899023 ON oekraine_verslagen');
        $this->addSql('ALTER TABLE oekraine_verslagen DROP contactsoort_id, DROP aanpassing_verslag');


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE oekraine_verslagen ADD contactsoort_id INT DEFAULT NULL, ADD aanpassing_verslag INT DEFAULT NULL');
        $this->addSql('ALTER TABLE oekraine_verslagen ADD CONSTRAINT FK_C15C9D6FD3899023 FOREIGN KEY (contactsoort_id) REFERENCES contactsoorts (id)');
        $this->addSql('CREATE INDEX IDX_C15C9D6FD3899023 ON oekraine_verslagen (contactsoort_id)');

    }
}
