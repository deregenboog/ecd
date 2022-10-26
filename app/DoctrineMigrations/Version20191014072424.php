<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191014072424 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE odp_duurthuisloos (id INT AUTO_INCREMENT NOT NULL, minVal INT DEFAULT NULL, maxVal INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE odp_werk (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) ');

        $this->addSql('ALTER TABLE odp_deelnemers ADD werk_id INT DEFAULT NULL, ADD duurThuisloos_id INT DEFAULT NULL, CHANGE wpi wpi TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE odp_deelnemers ADD CONSTRAINT FK_20283999D93D6B19 FOREIGN KEY (duurThuisloos_id) REFERENCES odp_duurthuisloos (id)');
        $this->addSql('ALTER TABLE odp_deelnemers ADD CONSTRAINT FK_202839991AAF70BD FOREIGN KEY (werk_id) REFERENCES odp_werk (id)');

        $this->addSql('CREATE INDEX IDX_20283999D93D6B19 ON odp_deelnemers (duurThuisloos_id)');
        $this->addSql('CREATE INDEX IDX_202839991AAF70BD ON odp_deelnemers (werk_id)');


        $this->addSql('INSERT INTO odp_duurthuisloos (`minVal`, `maxVal`) VALUES (null,3), (3,12), (12,24),(24,48),(48,null)');
        $this->addSql('INSERT INTO odp_werk (`label`) VALUES (\'Full time\'),(\'Parttime\'),(\'Kort werkloos < 12 mnd\'),(\'Werkzoeken, > 12 mnd\'),(\'Vrijwilligerswerk\'),(\'Geen werk\')');

    }

     public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_deelnemers DROP FOREIGN KEY FK_20283999D93D6B19');
        $this->addSql('ALTER TABLE odp_deelnemers DROP FOREIGN KEY FK_202839991AAF70BD');

        $this->addSql('DROP TABLE odp_duurthuisloos');
        $this->addSql('DROP TABLE odp_werk');

        $this->addSql('DROP INDEX IDX_20283999D93D6B19 ON odp_deelnemers');
        $this->addSql('DROP INDEX IDX_202839991AAF70BD ON odp_deelnemers');
        $this->addSql('ALTER TABLE odp_deelnemers DROP werk_id, DROP duurThuisloos_id, CHANGE wpi wpi TINYINT(1) DEFAULT \'0\' NOT NULL');


    }
}
