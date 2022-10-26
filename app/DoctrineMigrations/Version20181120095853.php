<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181120095853 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP VIEW eropuit_klanten');
        $this->addSql('DROP VIEW eropuit_vrijwilligers');
        $this->addSql('CREATE TABLE eropuit_klanten (id INT AUTO_INCREMENT NOT NULL, klant_id INT NOT NULL, uitschrijfreden_id INT DEFAULT NULL, inschrijfdatum DATE NOT NULL, uitschrijfdatum DATE DEFAULT NULL, communicatieEmail TINYINT(1) DEFAULT NULL, communicatieTelefoon TINYINT(1) DEFAULT NULL, communicatiePost TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, UNIQUE INDEX UNIQ_4B38B9823C427B2F (klant_id), INDEX IDX_4B38B9825D010236 (uitschrijfreden_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eropuit_vrijwilligers (id INT AUTO_INCREMENT NOT NULL, vrijwilliger_id INT NOT NULL, uitschrijfreden_id INT DEFAULT NULL, inschrijfdatum DATE NOT NULL, uitschrijfdatum DATE DEFAULT NULL, communicatieEmail TINYINT(1) DEFAULT NULL, communicatieTelefoon TINYINT(1) DEFAULT NULL, communicatiePost TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, UNIQUE INDEX UNIQ_3D566A3E76B43BDC (vrijwilliger_id), INDEX IDX_3D566A3E5D010236 (uitschrijfreden_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eropuit_uitschrijfredenen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE eropuit_klanten ADD CONSTRAINT FK_4B38B9823C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE eropuit_klanten ADD CONSTRAINT FK_4B38B9825D010236 FOREIGN KEY (uitschrijfreden_id) REFERENCES eropuit_uitschrijfredenen (id)');
        $this->addSql('ALTER TABLE eropuit_vrijwilligers ADD CONSTRAINT FK_3D566A3E76B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES vrijwilligers (id)');
        $this->addSql('ALTER TABLE eropuit_vrijwilligers ADD CONSTRAINT FK_3D566A3E5D010236 FOREIGN KEY (uitschrijfreden_id) REFERENCES eropuit_uitschrijfredenen (id)');
    }

    /**
     * @param Schema $schema
     */
     public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
