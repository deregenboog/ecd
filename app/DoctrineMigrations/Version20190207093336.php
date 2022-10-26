<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190207093336 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE zrm_reports ADD medewerker_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE zrm_reports ADD CONSTRAINT FK_C8EF119C3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_C8EF119C3D707F64 ON zrm_reports (medewerker_id)');
    }

     public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
