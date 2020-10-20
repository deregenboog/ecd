<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201016085835 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE klanten ADD corona_besmet_vanaf DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE `klanten` ADD INDEX `corona_besmet_idx` (`corona_besmet_vanaf`) ');
        $this->addSql('ALTER TABLE `registraties` ADD INDEX `klant_locatie_binnen_buiten` (`klant_id`, `locatie_id`, `binnen`, `buiten`) ');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE klanten DROP corona_besmet_vanaf');
        $this->addSql('ALTER TABLE `klanten` DROP INDEX `corona_besmet_idx`');
        $this->addSql('ALTER TABLE `klanten` DROP INDEX `klant_locatie_binnen_buiten`');

    }
}
