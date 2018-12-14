<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170522110053 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE schorsing_locatie (schorsing_id INT NOT NULL, locatie_id INT NOT NULL, INDEX IDX_52DA6766A52077DE (schorsing_id), INDEX IDX_52DA67664947630C (locatie_id), PRIMARY KEY(schorsing_id, locatie_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE schorsing_locatie ADD CONSTRAINT FK_52DA6766A52077DE FOREIGN KEY (schorsing_id) REFERENCES schorsingen (id)');
        $this->addSql('ALTER TABLE schorsing_locatie ADD CONSTRAINT FK_52DA67664947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id)');

        $this->addSql('INSERT INTO schorsing_locatie (schorsing_locatie.schorsing_id, schorsing_locatie.locatie_id) SELECT id, locatie_id FROM schorsingen');

        // This one may be executes when both BETA and PROD are migrated!
//         $this->addSql('ALTER TABLE schorsingen DROP locatie_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
