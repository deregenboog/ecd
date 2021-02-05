<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210205144213 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('CREATE TABLE clip_locaties (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, datum_van DATE NOT NULL, datum_tot DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');


        $this->addSql('ALTER TABLE clip_vrijwilliger_locatie DROP FOREIGN KEY FK_96F192054947630C');
        $this->addSql('ALTER TABLE clip_vrijwilliger_locatie ADD CONSTRAINT FK_96F192054947630C FOREIGN KEY (locatie_id) REFERENCES clip_locaties (id) ON DELETE CASCADE');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clip_vrijwilliger_locatie DROP FOREIGN KEY FK_96F192054947630C');

        $this->addSql('DROP TABLE clip_locaties');

        $this->addSql('ALTER TABLE clip_vrijwilliger_locatie DROP FOREIGN KEY FK_96F192054947630C');
        $this->addSql('ALTER TABLE clip_vrijwilliger_locatie ADD CONSTRAINT FK_96F192054947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id) ON DELETE CASCADE');


    }
}
