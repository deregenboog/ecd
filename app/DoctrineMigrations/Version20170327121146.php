<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170327121146 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE oekdeelname_oekdeelnamestatus (oekdeelname_id INT NOT NULL, oekdeelnamestatus_id INT NOT NULL, INDEX IDX_C62DDB85EBE6FB6A (oekdeelname_id), INDEX IDX_C62DDB85E1A284A4 (oekdeelnamestatus_id), PRIMARY KEY(oekdeelname_id, oekdeelnamestatus_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oek_deelname_statussen (id INT AUTO_INCREMENT NOT NULL, datum DATE NOT NULL, status VARCHAR(255) NOT NULL, oekDeelname_id INT NOT NULL, INDEX IDX_4CBB9BCD6D7A74BD (oekDeelname_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE oekdeelname_oekdeelnamestatus ADD CONSTRAINT FK_C62DDB85EBE6FB6A FOREIGN KEY (oekdeelname_id) REFERENCES oek_deelnames (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oekdeelname_oekdeelnamestatus ADD CONSTRAINT FK_C62DDB85E1A284A4 FOREIGN KEY (oekdeelnamestatus_id) REFERENCES oek_deelname_statussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oek_deelname_statussen ADD CONSTRAINT FK_4CBB9BCD6D7A74BD FOREIGN KEY (oekDeelname_id) REFERENCES oek_deelnames (id)');
        $this->addSql('ALTER TABLE oek_deelnames ADD oekDeelnameStatus_id INT DEFAULT NULL, DROP status');
        $this->addSql('ALTER TABLE oek_deelnames ADD CONSTRAINT FK_A6C1F2014DF034FD FOREIGN KEY (oekDeelnameStatus_id) REFERENCES oek_deelname_statussen (id)');
        $this->addSql('CREATE INDEX IDX_A6C1F2014DF034FD ON oek_deelnames (oekDeelnameStatus_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE oek_deelnames DROP FOREIGN KEY FK_A6C1F2014DF034FD');
        $this->addSql('ALTER TABLE oekdeelname_oekdeelnamestatus DROP FOREIGN KEY FK_C62DDB85E1A284A4');
        $this->addSql('DROP TABLE oekdeelname_oekdeelnamestatus');
        $this->addSql('DROP TABLE oek_deelname_statussen');
        $this->addSql('DROP INDEX IDX_A6C1F2014DF034FD ON oek_deelnames');
        $this->addSql('ALTER TABLE oek_deelnames ADD status VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP oekDeelnameStatus_id');
    }
}
