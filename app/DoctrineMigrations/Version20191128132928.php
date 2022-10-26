<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191128132928 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        
    }

     public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE uhk_deelnemers DROP FOREIGN KEY FK_739D2F67B395DC75');
        $this->addSql('ALTER TABLE uhk_deelnemers DROP FOREIGN KEY FK_739D2F673D707F64');
        $this->addSql('DROP INDEX IDX_739D2F67B395DC75 ON uhk_deelnemers');
        $this->addSql('DROP INDEX IDX_739D2F673D707F64 ON uhk_deelnemers');
        $this->addSql('ALTER TABLE uhk_deelnemers ADD aanmeldNaam VARCHAR(255) NOT NULL COLLATE utf8_general_ci, DROP aanmeldNaam_id');

    }
}
