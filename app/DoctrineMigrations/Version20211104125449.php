<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211104125449 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE tw_inkomen ADD active TINYINT(1) NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE tw_deelnemers CHANGE intakeStatus_id intakeStatus_id INT NULL');
        $this->addSql('ALTER TABLE tw_traplopen ADD active TINYINT(1) NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE tw_moscreening ADD active TINYINT(1) NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE tw_softdrugs ADD active TINYINT(1) NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE tw_regio ADD active TINYINT(1) NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE tw_alcohol ADD active TINYINT(1) NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE tw_roken ADD active TINYINT(1) NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE tw_duurthuisloos ADD active TINYINT(1) NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE tw_ritme ADD active TINYINT(1) NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE tw_werk ADD active TINYINT(1) NOT NULL DEFAULT 1');

    }

     public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tw_alcohol DROP active');
        $this->addSql('ALTER TABLE tw_duurthuisloos DROP active');
        $this->addSql('ALTER TABLE tw_inkomen DROP active');
        $this->addSql('ALTER TABLE tw_moscreening DROP active');
        $this->addSql('ALTER TABLE tw_regio DROP active');
        $this->addSql('ALTER TABLE tw_ritme DROP active');
        $this->addSql('ALTER TABLE tw_roken DROP active');
        $this->addSql('ALTER TABLE tw_softdrugs DROP active');
        $this->addSql('ALTER TABLE tw_traplopen DROP active');
        $this->addSql('ALTER TABLE tw_werk DROP active');

    }
}
