<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211224103730 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tw_huuraanbiedingen CHANGE medewerker_id medewerker_id INT DEFAULT NULL');
        $this->addSql("UPDATE `geslachten` SET `afkorting` = 'A', `volledig` = 'Anders' WHERE `geslachten`.`afkorting` = 'X';");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tw_huuraanbiedingen CHANGE medewerker_id medewerker_id INT NOT NULL');
        $this->addSql("UPDATE `geslachten` SET `afkorting` = 'X', `volledig` = 'Non-binair' WHERE `geslachten`.`afkorting` = 'A';");
    }
}
