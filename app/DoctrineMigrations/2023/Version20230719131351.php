<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230719131351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add discr map for STI of verslagen for dagbesteding.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE dagbesteding_verslagen ADD discr VARCHAR(15) NOT NULL DEFAULT \'verslag\'');
    }

    public function down(Schema $schema): void
    {

        $this->addSql('ALTER TABLE dagbesteding_verslagen DROP discr');
    }
}
