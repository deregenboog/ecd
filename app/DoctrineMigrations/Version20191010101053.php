<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191010101053 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE odp_huurbudget (id INT AUTO_INCREMENT NOT NULL, minVal INT DEFAULT NULL, maxVal INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE odp_deelnemers ADD huurBudget_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_deelnemers ADD CONSTRAINT FK_20283999C8F722F4 FOREIGN KEY (huurBudget_id) REFERENCES odp_huurbudget (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE odp_deelnemers DROP FOREIGN KEY FK_20283999C8F722F4');
        $this->addSql('ALTER TABLE odp_deelnemers DROP huurBudget_id');
        $this->addSql('DROP TABLE odp_huurbudget');
    }
}
