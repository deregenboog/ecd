<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210205162440 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE klanten SET geboortedatum = "1900-01-01" WHERE CAST(geboortedatum AS CHAR(10)) =  "0000-00-00"');
        $this->addSql('ALTER TABLE klanten ADD maatschappelijkWerker_id INT DEFAULT NULL');

        $this->addSql('ALTER TABLE klanten ADD CONSTRAINT FK_F538C5BCEB8E119C FOREIGN KEY (maatschappelijkWerker_id) REFERENCES medewerkers (id)');
//        $this->addSql('CREATE UNIQUE INDEX UNIQ_F538C5BC9393F8FE ON klanten (partner_id)');
        $this->addSql('CREATE INDEX IDX_F538C5BCEB8E119C ON klanten (maatschappelijkWerker_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE klanten DROP FOREIGN KEY FK_F538C5BCEB8E119C');
        $this->addSql('DROP INDEX IDX_F538C5BCEB8E119C ON klanten');
        $this->addSql('ALTER TABLE klanten DROP maatschappelijkWerker_id');
    }
}
