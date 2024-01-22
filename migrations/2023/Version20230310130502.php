<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310130502 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'remove deprecated columsn from registratie';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `registraties` DROP `klant_id_before_constraint`');
        $this->addSql('ALTER TABLE `registraties` DROP `locatie_id_before_constraint`');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
