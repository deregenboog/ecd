<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190117121119 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intakes ADD zrm_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AEC8250F57 FOREIGN KEY (zrm_id) REFERENCES zrm_reports (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AB70F5AEC8250F57 ON intakes (zrm_id)');
        $this->addSql('UPDATE intakes
            SET intakes.zrm_id = (
                SELECT MAX(inloop_intake_zrm.zrm_id)
                FROM inloop_intake_zrm
                WHERE intakes.id = inloop_intake_zrm.intake_id
                GROUP BY inloop_intake_zrm.intake_id
            )
            WHERE intakes.zrm_id IS NULL
        ');

        $this->addSql('ALTER TABLE iz_intakes ADD zrm_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE iz_intakes ADD CONSTRAINT FK_11EFC53DC8250F57 FOREIGN KEY (zrm_id) REFERENCES zrm_reports (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_11EFC53DC8250F57 ON iz_intakes (zrm_id)');
        $this->addSql('UPDATE iz_intakes
            SET iz_intakes.zrm_id = (
                SELECT MAX(iz_intake_zrm.zrm_id)
                FROM iz_intake_zrm
                WHERE iz_intakes.id = iz_intake_zrm.intake_id
                GROUP BY iz_intake_zrm.intake_id
            )
            WHERE iz_intakes.zrm_id IS NULL
        ');
    }

    public function down(Schema $schema) : void
    {
        $this->throwIrreversibleMigrationException();
    }
}
