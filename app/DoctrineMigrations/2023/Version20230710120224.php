<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230710120224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'change access attributes for oekraine verslagen';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("START TRANSACTION");
        $this->addSql('UPDATE oekraine_verslagen SET oekraine_verslagen.accessType = 5 WHERE oekraine_verslagen.accessType = 1');
        $this->addSql('UPDATE oekraine_verslagen SET oekraine_verslagen.accessType = 1 WHERE oekraine_verslagen.accessType = 2');
        $this->addSql('UPDATE oekraine_verslagen SET oekraine_verslagen.accessType = 2 WHERE oekraine_verslagen.accessType = 5');

        $this->addSql('UPDATE oekraine_verslagen SET oekraine_verslagen.verslagType = 5 WHERE oekraine_verslagen.verslagType = 1');
        $this->addSql('UPDATE oekraine_verslagen SET oekraine_verslagen.verslagType = 1 WHERE oekraine_verslagen.verslagType = 2');
        $this->addSql('UPDATE oekraine_verslagen SET oekraine_verslagen.verslagType = 2 WHERE oekraine_verslagen.verslagType = 5');

        $this->addSql("COMMIT");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("START TRANSACTION");
        $this->addSql('UPDATE oekraine_verslagen SET oekraine_verslagen.accessType = 5 WHERE oekraine_verslagen.accessType = 2');
        $this->addSql('UPDATE oekraine_verslagen SET oekraine_verslagen.accessType = 2 WHERE oekraine_verslagen.accessType = 1');
        $this->addSql('UPDATE oekraine_verslagen SET oekraine_verslagen.accessType = 1 WHERE oekraine_verslagen.accessType = 5');

        $this->addSql('UPDATE oekraine_verslagen SET oekraine_verslagen.verslagType = 5 WHERE oekraine_verslagen.verslagType = 2');
        $this->addSql('UPDATE oekraine_verslagen SET oekraine_verslagen.verslagType = 2 WHERE oekraine_verslagen.verslagType = 1');
        $this->addSql('UPDATE oekraine_verslagen SET oekraine_verslagen.verslagType = 1 WHERE oekraine_verslagen.verslagType = 5');
        $this->addSql("COMMIT");
    }
}
