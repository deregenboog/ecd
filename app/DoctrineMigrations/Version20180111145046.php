<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180111145046 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE oek_vrijwilligers (id INT AUTO_INCREMENT NOT NULL, vrijwilliger_id INT NOT NULL, medewerker_id INT NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, UNIQUE INDEX UNIQ_2D75CD3476B43BDC (vrijwilliger_id), INDEX IDX_2D75CD343D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE oek_vrijwilligers ADD CONSTRAINT FK_2D75CD3476B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES vrijwilligers (id)');
        $this->addSql('ALTER TABLE oek_vrijwilligers ADD CONSTRAINT FK_2D75CD343D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE TABLE oek_vrijwilliger_memo (vrijwilliger_id INT NOT NULL, memo_id INT NOT NULL, INDEX IDX_5ED2E90C76B43BDC (vrijwilliger_id), UNIQUE INDEX UNIQ_5ED2E90CB4D32439 (memo_id), PRIMARY KEY(vrijwilliger_id, memo_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oek_vrijwilliger_document (vrijwilliger_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_725F2FCA76B43BDC (vrijwilliger_id), UNIQUE INDEX UNIQ_725F2FCAC33F7837 (document_id), PRIMARY KEY(vrijwilliger_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oek_documenten (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, naam VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_CE730FA23D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oek_memos (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, datum DATETIME NOT NULL, onderwerp VARCHAR(255) NOT NULL, memo LONGTEXT NOT NULL, intake TINYINT(1) NOT NULL, INDEX IDX_8F8DED693D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE oek_vrijwilliger_memo ADD CONSTRAINT FK_5ED2E90C76B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES oek_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oek_vrijwilliger_memo ADD CONSTRAINT FK_5ED2E90CB4D32439 FOREIGN KEY (memo_id) REFERENCES oek_memos (id)');
        $this->addSql('ALTER TABLE oek_vrijwilliger_document ADD CONSTRAINT FK_725F2FCA76B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES oek_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oek_vrijwilliger_document ADD CONSTRAINT FK_725F2FCAC33F7837 FOREIGN KEY (document_id) REFERENCES oek_documenten (id)');
        $this->addSql('ALTER TABLE oek_documenten ADD CONSTRAINT FK_CE730FA23D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE oek_memos ADD CONSTRAINT FK_8F8DED693D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
