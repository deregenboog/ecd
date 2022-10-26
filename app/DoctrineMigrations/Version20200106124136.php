<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200106124136 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE inloop_vrijwilligers ADD stagiair TINYINT(1) NOT NULL, ADD startdatum DATE DEFAULT NULL, ADD notitieIntake VARCHAR(255) DEFAULT NULL, ADD datumNotitieIntake DATE DEFAULT NULL, ADD medewerkerLocatie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE inloop_vrijwilligers ADD CONSTRAINT FK_56110480EA9C84FE FOREIGN KEY (medewerkerLocatie_id) REFERENCES locaties (id)');
        $this->addSql('CREATE INDEX IDX_56110480EA9C84FE ON inloop_vrijwilligers (medewerkerLocatie_id)');
        $this->addSql('ALTER TABLE inloop_vrijwilliger_document DROP FOREIGN KEY FK_6401B15DC33F7837');
        $this->addSql('ALTER TABLE inloop_vrijwilliger_document ADD CONSTRAINT FK_6401B15DC33F7837 FOREIGN KEY (document_id) REFERENCES inloop_documenten (id) ON DELETE CASCADE');


        $this->addSql('CREATE TABLE inloop_deelnames (id INT AUTO_INCREMENT NOT NULL, inloop_vrijwilliger_id INT NOT NULL, datum DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, inloopTraining_id INT NOT NULL, INDEX IDX_CFB194F341D2A6EF (inloopTraining_id), INDEX IDX_CFB194F3B280D297 (inloop_vrijwilliger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inloop_training (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inloop_deelnames ADD CONSTRAINT FK_CFB194F341D2A6EF FOREIGN KEY (inloopTraining_id) REFERENCES inloop_training (id)');
        $this->addSql('ALTER TABLE inloop_deelnames ADD CONSTRAINT FK_CFB194F3B280D297 FOREIGN KEY (inloop_vrijwilliger_id) REFERENCES inloop_vrijwilligers (id)');
        $this->addSql('ALTER TABLE inloop_vrijwilligers ADD trainingOverig VARCHAR(255) DEFAULT NULL, ADD trainingOverigDatum VARCHAR(255) DEFAULT NULL');


    }

     public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE inloop_vrijwilliger_document DROP FOREIGN KEY FK_6401B15DC33F7837');
        $this->addSql('ALTER TABLE inloop_vrijwilliger_document ADD CONSTRAINT FK_6401B15DC33F7837 FOREIGN KEY (document_id) REFERENCES inloop_documenten (id)');
        $this->addSql('ALTER TABLE inloop_vrijwilligers DROP FOREIGN KEY FK_56110480EA9C84FE');
        $this->addSql('DROP INDEX IDX_56110480EA9C84FE ON inloop_vrijwilligers');
        $this->addSql('ALTER TABLE inloop_vrijwilligers DROP stagiair, DROP startdatum, DROP notitieIntake, DROP datumNotitieIntake, DROP medewerkerLocatie_id');

        $this->addSql('ALTER TABLE inloop_deelnames DROP FOREIGN KEY FK_CFB194F341D2A6EF');
        $this->addSql('DROP TABLE inloop_deelnames');
        $this->addSql('DROP TABLE inloop_training');
        $this->addSql('ALTER TABLE inloop_vrijwilligers DROP trainingOverig, DROP trainingOverigDatum');

    }
}
