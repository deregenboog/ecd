<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190328142716 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE documenten (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, filename VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, discr VARCHAR(15) NOT NULL, INDEX IDX_8751AD653D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE documenten ADD CONSTRAINT FK_8751AD653D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE TABLE app_vrijwilliger_document (vrijwilliger_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_D5E9A8C976B43BDC (vrijwilliger_id), UNIQUE INDEX UNIQ_D5E9A8C9C33F7837 (document_id), PRIMARY KEY(vrijwilliger_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_vrijwilliger_document ADD CONSTRAINT FK_D5E9A8C976B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_vrijwilliger_document ADD CONSTRAINT FK_D5E9A8C9C33F7837 FOREIGN KEY (document_id) REFERENCES documenten (id)');
    }

    public function down(Schema $schema): void
    {
    }
}
