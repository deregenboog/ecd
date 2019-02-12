<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190212100701 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intakes ADD overigen_toegang_van DATE DEFAULT NULL');
        $this->addSql("UPDATE klanten k
            INNER JOIN intakes i ON i.id = k.laste_intake_id
            SET i.overigen_toegang_van = DATE_ADD(k.first_intake_date, INTERVAL 3 MONTH)
            WHERE i.verblijfstatus_id = 7
            AND k.first_intake_date < '2017-06-01'
            AND i.overigen_toegang_van IS NULL");
        $this->addSql("UPDATE klanten k
            INNER JOIN intakes i ON i.id = k.laste_intake_id
            SET i.overigen_toegang_van = DATE_ADD(k.first_intake_date, INTERVAL 6 MONTH)
            WHERE i.verblijfstatus_id = 7
            AND k.first_intake_date >= '2017-06-01'
            AND i.overigen_toegang_van IS NULL");
    }

    public function down(Schema $schema) : void
    {
    }
}
