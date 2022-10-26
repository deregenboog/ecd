<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210827082650 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE mw_vrijwilligers DROP FOREIGN KEY FK_CFC2BAE3EA9C84FE');
        $this->addSql('DROP INDEX IDX_CFC2BAE3EA9C84FE ON mw_vrijwilligers');
        $this->addSql('ALTER TABLE mw_vrijwilligers DROP startdatum, DROP medewerkerLocatie_id');


        $this->addSql('ALTER TABLE odp_vrijwilligers DROP FOREIGN KEY FK_198B6514EA9C84FE');
        $this->addSql('DROP INDEX IDX_198B6514EA9C84FE ON odp_vrijwilligers');
    }

     public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mw_vrijwilligers ADD startdatum DATE DEFAULT NULL, ADD medewerkerLocatie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mw_vrijwilligers ADD CONSTRAINT FK_CFC2BAE3EA9C84FE FOREIGN KEY (medewerkerLocatie_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_CFC2BAE3EA9C84FE ON mw_vrijwilligers (medewerkerLocatie_id)');


        $this->addSql('ALTER TABLE odp_vrijwilligers ADD startdatum DATE DEFAULT NULL, ADD medewerkerLocatie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_vrijwilligers ADD CONSTRAINT FK_198B6514EA9C84FE FOREIGN KEY (medewerkerLocatie_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_198B6514EA9C84FE ON odp_vrijwilligers (medewerkerLocatie_id)');

    }
}
