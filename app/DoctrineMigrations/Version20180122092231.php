<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180122092231 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE inloop_vrijwilligers (id INT AUTO_INCREMENT NOT NULL, vrijwilliger_id INT NOT NULL, medewerker_id INT NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, UNIQUE INDEX UNIQ_5611048076B43BDC (vrijwilliger_id), INDEX IDX_561104803D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inloop_vrijwilliger_memo (vrijwilliger_id INT NOT NULL, memo_id INT NOT NULL, INDEX IDX_94FA9B1976B43BDC (vrijwilliger_id), UNIQUE INDEX UNIQ_94FA9B19B4D32439 (memo_id), PRIMARY KEY(vrijwilliger_id, memo_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inloop_vrijwilliger_document (vrijwilliger_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_6401B15D76B43BDC (vrijwilliger_id), UNIQUE INDEX UNIQ_6401B15DC33F7837 (document_id), PRIMARY KEY(vrijwilliger_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inloop_documenten (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, naam VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_9DA9ECF43D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inloop_memos (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, datum DATETIME NOT NULL, onderwerp VARCHAR(255) NOT NULL, memo LONGTEXT NOT NULL, intake TINYINT(1) NOT NULL, INDEX IDX_9ACAE40D3D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inloop_vrijwilligers ADD CONSTRAINT FK_5611048076B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES vrijwilligers (id)');
        $this->addSql('ALTER TABLE inloop_vrijwilligers ADD CONSTRAINT FK_561104803D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE inloop_vrijwilliger_memo ADD CONSTRAINT FK_94FA9B1976B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES inloop_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inloop_vrijwilliger_memo ADD CONSTRAINT FK_94FA9B19B4D32439 FOREIGN KEY (memo_id) REFERENCES inloop_memos (id)');
        $this->addSql('ALTER TABLE inloop_vrijwilliger_document ADD CONSTRAINT FK_6401B15D76B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES inloop_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inloop_vrijwilliger_document ADD CONSTRAINT FK_6401B15DC33F7837 FOREIGN KEY (document_id) REFERENCES inloop_documenten (id)');
        $this->addSql('ALTER TABLE inloop_documenten ADD CONSTRAINT FK_9DA9ECF43D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE inloop_memos ADD CONSTRAINT FK_9ACAE40D3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
