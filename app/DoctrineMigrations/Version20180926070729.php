<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180926070729 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE amoc_landen (id INT AUTO_INCREMENT NOT NULL, land_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_2B24A60A1994904A (land_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE amoc_landen ADD CONSTRAINT FK_2B24A60A1994904A FOREIGN KEY (land_id) REFERENCES landen (id)');
        $this->addSql('INSERT INTO amoc_landen (land_id)
            SELECT landen.id FROM landen WHERE landen.id IN (5002, 5009, 5010, 5015, 5017, 5039, 5040, 5049, 6002, 6003, 6007, 6018, 6029, 6037, 6039, 6066, 6067, 7003, 7024, 7028, 7044, 7047, 7050, 7064, 7065, 7066)
        ');
    }

    /**
     * @param Schema $schema
     */
     public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
