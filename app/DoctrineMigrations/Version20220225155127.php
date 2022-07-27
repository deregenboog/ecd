<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220225155127 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dagbesteding_trajecten DROP FOREIGN KEY FK_670A67F239F86A1D');

//        $this->addSql('CREATE TABLE dagbesteding_trajectcoaches (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT DEFAULT NULL, naam VARCHAR(255) DEFAULT NULL, display_name VARCHAR(255) DEFAULT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_C703865F3D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
//        $this->addSql('ALTER TABLE dagbesteding_trajectcoaches ADD CONSTRAINT FK_C703865F3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
//        $this->addSql('DROP TABLE dagbesteding_trajectbegeleiders');

        $this->addSql('RENAME TABLE dagbesteding_trajectbegeleiders TO dagbesteding_trajectcoaches;');

        $this->addSql('DROP INDEX IDX_670A67F239F86A1D ON dagbesteding_trajecten');
        $this->addSql('ALTER TABLE dagbesteding_trajecten CHANGE begeleider_id trajectcoach_id INT NOT NULL');
        $this->addSql('ALTER TABLE dagbesteding_trajecten ADD CONSTRAINT FK_670A67F2AEDCD25A FOREIGN KEY (trajectcoach_id) REFERENCES dagbesteding_trajectcoaches (id)');
        $this->addSql('CREATE INDEX IDX_670A67F2AEDCD25A ON dagbesteding_trajecten (trajectcoach_id)');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    }
}
