<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200630070653 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE odp_vormvanovereenkomst (id INT AUTO_INCREMENT NOT NULL, label varchar(255) NOT NULL, startdate DATE NOT NULL, enddate DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE odp_huuraanbiedingen ADD COLUMN vormvanovereenkomst_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_huuraanbiedingen ADD CONSTRAINT FK_FA204F87522CF24B FOREIGN KEY (vormvanovereenkomst_id) REFERENCES odp_vormvanovereenkomst (id)');
        $this->addSql('CREATE INDEX IDX_FA204F87522CF24B ON odp_huuraanbiedingen (vormvanovereenkomst_id)');
        $this->addSql('INSERT INTO odp_vormvanovereenkomst (label,startdate) VALUES
            ("Onder de pannen",NOW()),
            ("Parentshouse", NOW()),
            ("Intermediare verhuur", NOW()),
            ("Wonen 200", NOW()),
            ("Tijdelijk beheer", NOW()),
            ("Anders", NOW())
         ');
        $this->addSql('UPDATE odp_huuraanbiedingen SET vormvanovereenkomst_id = 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_huuraanbiedingen DROP FOREIGN KEY FK_FA204F87522CF24B');

        $this->addSql('DROP TABLE odp_vormvanovereenkomst');

        $this->addSql('ALTER TABLE odp_huuraanbiedingen DROP COLUMN vormvanovereenkomst_id');
    }
}
