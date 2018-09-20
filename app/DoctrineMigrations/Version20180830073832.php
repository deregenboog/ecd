<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180830073832 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ga_documenten (id INT AUTO_INCREMENT NOT NULL, vrijwilliger_id INT DEFAULT NULL, medewerker_id INT NOT NULL, naam VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_409E561276B43BDC (vrijwilliger_id), INDEX IDX_409E56123D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ga_documenten ADD CONSTRAINT FK_409E561276B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES vrijwilligers (id)');
        $this->addSql('ALTER TABLE ga_documenten ADD CONSTRAINT FK_409E56123D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');

        $this->addSql("INSERT INTO ga_documenten (vrijwilliger_id, medewerker_id, naam, filename, created, modified)
            SELECT attachments.foreign_key, attachments.user_id, attachments.title, attachments.basename, attachments.created, attachments.modified
            FROM attachments
            INNER JOIN vrijwilligers ON attachments.foreign_key = vrijwilligers.id
                AND attachments.model = 'Vrijwilliger'
            WHERE `group` = 'Groepsactiviteit'
                AND is_active = 1");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
