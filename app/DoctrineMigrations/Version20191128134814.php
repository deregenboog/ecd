<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191128134814 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //$this->addSql('ALTER TABLE uhk_deelnemers DROP FOREIGN KEY FK_739D2F67B395DC75');
//        $this->addSql('DROP INDEX IDX_739D2F67B395DC75 ON uhk_deelnemers');
        $this->addSql('ALTER TABLE uhk_deelnemers CHANGE aanmeldNaam aanmelder_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE uhk_deelnemers ADD CONSTRAINT FK_739D2F6775E6B4CA FOREIGN KEY (aanmelder_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_739D2F6775E6B4CA ON uhk_deelnemers (aanmelder_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //$this->addSql('ALTER TABLE uhk_deelnemers DROP FOREIGN KEY FK_739D2F6775E6B4CA');
        //$this->addSql('DROP INDEX IDX_739D2F6775E6B4CA ON uhk_deelnemers');
        $this->addSql('ALTER TABLE uhk_deelnemers CHANGE aanmelder_id aanmeldNaam_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE uhk_deelnemers ADD CONSTRAINT FK_739D2F67B395DC75 FOREIGN KEY (aanmeldNaam_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_739D2F67B395DC75 ON uhk_deelnemers (aanmeldNaam_id)');

    }
}
