<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180704102849 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE iz_deelnemerstatussen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, deletedAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iz_deelnemers ADD status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE iz_deelnemers ADD CONSTRAINT FK_89B5B51C6BF700BD FOREIGN KEY (status_id) REFERENCES iz_deelnemerstatussen (id)');
        $this->addSql('CREATE INDEX IDX_89B5B51C6BF700BD ON iz_deelnemers (status_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE iz_deelnemers DROP FOREIGN KEY FK_89B5B51C6BF700BD');
        $this->addSql('DROP TABLE iz_deelnemerstatussen');
        $this->addSql('DROP INDEX IDX_89B5B51C6BF700BD ON iz_deelnemers');
        $this->addSql('ALTER TABLE iz_deelnemers DROP status_id');
    }
}
