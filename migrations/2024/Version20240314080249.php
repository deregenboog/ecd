<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240314080249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Verslagen toevoegen aan activiteiten';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ga_verslagen ADD activiteit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ga_verslagen ADD CONSTRAINT FK_33E9790A5A8A0A1 FOREIGN KEY (activiteit_id) REFERENCES ga_activiteiten (id)');
        $this->addSql('CREATE INDEX IDX_33E9790A5A8A0A1 ON ga_verslagen (activiteit_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ga_verslagen DROP FOREIGN KEY FK_33E9790A5A8A0A1');
        $this->addSql('DROP INDEX IDX_33E9790A5A8A0A1 ON ga_verslagen');
        $this->addSql('ALTER TABLE ga_verslagen DROP activiteit_id');
    }
}
