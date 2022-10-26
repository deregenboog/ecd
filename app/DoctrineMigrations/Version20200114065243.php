<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200114065243 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');



        $this->addSql('ALTER TABLE inloop_vrijwilligers ADD locatie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE inloop_vrijwilligers ADD CONSTRAINT FK_561104804947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id)');

//        $this->addSql('')
        $this->addSql('CREATE INDEX IDX_561104804947630C ON inloop_vrijwilligers (locatie_id)');
//        $this->addSql('DROP TABLE inloop_vrijwilliger_locatie');
    }

     public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        //$this->addSql('CREATE TABLE inloop_vrijwilliger_locatie (vrijwilliger_id INT NOT NULL, locatie_id INT NOT NULL, INDEX IDX_A1776D9F76B43BDC (vrijwilliger_id), INDEX IDX_A1776D9F4947630C (locatie_id), PRIMARY KEY(vrijwilliger_id, locatie_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');


        //$this->addSql('ALTER TABLE inloop_vrijwilliger_locatie ADD CONSTRAINT FK_A1776D9F4947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id) ON DELETE CASCADE');
       // $this->addSql('ALTER TABLE inloop_vrijwilliger_locatie ADD CONSTRAINT FK_A1776D9F76B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES inloop_vrijwilligers (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE inloop_vrijwilligers DROP FOREIGN KEY FK_561104804947630C');
        $this->addSql('DROP INDEX IDX_561104804947630C ON inloop_vrijwilligers');
        $this->addSql('ALTER TABLE inloop_vrijwilligers DROP locatie_id');

    }
}
