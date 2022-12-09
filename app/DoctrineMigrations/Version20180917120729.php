<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180917120729 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE schorsingen
            CHANGE locatiehoofd locatiehoofd VARCHAR(100) DEFAULT NULL,
            CHANGE remark remark LONGTEXT DEFAULT NULL,
            CHANGE bijzonderheden bijzonderheden LONGTEXT DEFAULT NULL,
            CHANGE gezien gezien TINYINT(1) NOT NULL DEFAULT 0,
            CHANGE aangifte aangifte TINYINT(1) NOT NULL DEFAULT 0,
            CHANGE nazorg nazorg TINYINT(1) NOT NULL DEFAULT 0,
            CHANGE agressie agressie TINYINT(1) NOT NULL DEFAULT 0,
            CHANGE created created DATETIME NOT NULL,
            CHANGE modified modified DATETIME NOT NULL
        ');

        $this->addSql("UPDATE schorsingen SET locatiehoofd = NULL WHERE locatiehoofd = ''");
        $this->addSql("UPDATE schorsingen SET remark = NULL WHERE remark = ''");
        $this->addSql("UPDATE schorsingen SET bijzonderheden = NULL WHERE bijzonderheden = ''");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
