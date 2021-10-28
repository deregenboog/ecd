<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210923101810 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tw_intakestatus (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('INSERT INTO tw_intakestatus (naam,active,created,modified) VALUES
            ("Onbekend",1,NOW(),NOW()),
            ("Geen",1,NOW(),NOW()),
            ("Telefonisch",1,NOW(),NOW()),
            ("Live",1,NOW(),NOW())
            ');
        $this->addSql('ALTER TABLE tw_deelnemers ADD intakeStatus_id INT NOT NULL DEFAULT 1,CHANGE `medewerker_id` `medewerker_id` INT(11) NULL');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E43172562657F54F FOREIGN KEY (intakeStatus_id) REFERENCES tw_intakestatus (id)');
        $this->addSql('CREATE INDEX IDX_E43172562657F54F ON tw_deelnemers (intakeStatus_id)');
        $this->addSql('ALTER TABLE tw_pandeigenaar RENAME INDEX idx_e43172565721a654 TO IDX_131D6069EBDA8F64');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E43172562657F54F');
        $this->addSql('DROP TABLE tw_intakestatus');

        $this->addSql('DROP INDEX IDX_E43172562657F54F ON tw_deelnemers');
        $this->addSql('ALTER TABLE tw_deelnemers DROP intakeStatus_id');
        $this->addSql('ALTER TABLE tw_pandeigenaar RENAME INDEX idx_131d6069ebda8f64 TO IDX_E43172565721A654');
    }
}
