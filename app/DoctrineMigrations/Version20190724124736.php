<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190724124736 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO `inkomens` (`id`, `naam`, `datum_van`, `datum_tot`, `created`, `modified`) VALUES (NULL, \'Geen\', \'2010-01-06\', NULL, NOW(), NOW());');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM `inkomens` WHERE `naam` = \'Geen\'');
    }
}
