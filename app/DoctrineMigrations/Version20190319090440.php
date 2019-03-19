<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190319090440 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE buurtboerderij_afsluitredenen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE buurtboerderij_vrijwilligers (id INT AUTO_INCREMENT NOT NULL, vrijwilliger_id INT NOT NULL, afsluitreden_id INT DEFAULT NULL, medewerker_id INT NOT NULL, aanmelddatum DATE NOT NULL, afsluitdatum DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, UNIQUE INDEX UNIQ_57645FD776B43BDC (vrijwilliger_id), INDEX IDX_57645FD7CA12F7AE (afsluitreden_id), INDEX IDX_57645FD73D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE buurtboerderij_vrijwilligers ADD CONSTRAINT FK_57645FD776B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES vrijwilligers (id)');
        $this->addSql('ALTER TABLE buurtboerderij_vrijwilligers ADD CONSTRAINT FK_57645FD7CA12F7AE FOREIGN KEY (afsluitreden_id) REFERENCES buurtboerderij_afsluitredenen (id)');
        $this->addSql('ALTER TABLE buurtboerderij_vrijwilligers ADD CONSTRAINT FK_57645FD73D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
    }

    public function down(Schema $schema) : void
    {
        $this->throwIrreversibleMigrationException();
    }
}
