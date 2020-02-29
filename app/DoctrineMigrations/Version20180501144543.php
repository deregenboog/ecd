<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180501144543 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE klanten ADD CONSTRAINT FK_F538C5BC46708ED5 FOREIGN KEY (werkgebied) REFERENCES werkgebieden (naam)');
        $this->addSql('ALTER TABLE klanten ADD CONSTRAINT FK_F538C5BCFB02B9C2 FOREIGN KEY (postcodegebied) REFERENCES ggw_gebieden (naam)');
        $this->addSql('ALTER TABLE vrijwilligers ADD CONSTRAINT FK_F0C4D23746708ED5 FOREIGN KEY (werkgebied) REFERENCES werkgebieden (naam)');
        $this->addSql('ALTER TABLE vrijwilligers ADD CONSTRAINT FK_F0C4D237FB02B9C2 FOREIGN KEY (postcodegebied) REFERENCES ggw_gebieden (naam)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
