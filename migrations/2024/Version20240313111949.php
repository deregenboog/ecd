<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240313111949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Medewerker toevoegen aan schorsingen en incidenten.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE schorsingen ADD medewerker_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE schorsingen ADD CONSTRAINT FK_9E658EBF3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_9E658EBF3D707F64 ON schorsingen (medewerker_id)');

        $this->addSql('ALTER TABLE inloop_incidenten ADD medewerker_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE inloop_incidenten ADD CONSTRAINT FK_F85DD4753D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_F85DD4753D707F64 ON inloop_incidenten (medewerker_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE schorsingen DROP FOREIGN KEY FK_9E658EBF3D707F64');
        $this->addSql('DROP INDEX IDX_9E658EBF3D707F64 ON schorsingen');
        $this->addSql('ALTER TABLE schorsingen DROP medewerker_id');

        $this->addSql('ALTER TABLE inloop_incidenten DROP FOREIGN KEY FK_F85DD4753D707F64');
        $this->addSql('DROP INDEX IDX_F85DD4753D707F64 ON inloop_incidenten');
        $this->addSql('ALTER TABLE inloop_incidenten DROP medewerker_id');
    }
}
