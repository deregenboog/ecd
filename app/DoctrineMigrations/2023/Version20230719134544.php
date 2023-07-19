<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230719134544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Replace newline with br for dagbesteding verslagen';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE dagbesteding_verslagen SET opmerking = REPLACE(opmerking,"\n","<br/>")');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE dagbesteding_verslagen SET opmerking = REPLACE(opmerking,"<br/>","\n")');

    }
}
