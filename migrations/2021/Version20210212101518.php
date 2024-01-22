<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210212101518 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
INSERT IGNORE INTO `villa_training` (`id`, `naam`, `active`) VALUES(1, 'Omgaan met verbale agressie (1)', 1);
INSERT IGNORE INTO `villa_training` (`id`, `naam`, `active`) VALUES(2, 'Werken met groepen', 1);
INSERT IGNORE INTO `villa_training` (`id`, `naam`, `active`) VALUES(3, 'Overig', 1)");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
