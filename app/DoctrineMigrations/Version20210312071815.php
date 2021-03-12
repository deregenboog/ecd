<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210312071815 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_deelnemers ADD zrm_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_deelnemers ADD CONSTRAINT FK_20283999C8250F57 FOREIGN KEY (zrm_id) REFERENCES zrm_reports (id)');
        $this->addSql('CREATE INDEX IDX_20283999C8250F57 ON odp_deelnemers (zrm_id)');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE odp_deelnemers DROP FOREIGN KEY FK_20283999C8250F57');
        $this->addSql('DROP INDEX IDX_20283999C8250F57 ON odp_deelnemers');
        $this->addSql('ALTER TABLE odp_deelnemers DROP zrm_id');

    }
}
