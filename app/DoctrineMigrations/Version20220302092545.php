<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220302092545 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dagbesteding_beschikbaarheid (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, maandagVan TIME DEFAULT NULL, maandagTot TIME DEFAULT NULL, dinsdagVan TIME DEFAULT NULL, dinsdagTot TIME DEFAULT NULL, woensdagVan TIME DEFAULT NULL, woensdagTot TIME DEFAULT NULL, donderdagVan TIME DEFAULT NULL, donderdagTot TIME DEFAULT NULL, vrijdagVan TIME DEFAULT NULL, vrijdagTot TIME DEFAULT NULL, zaterdagVan TIME DEFAULT NULL, zaterdagTot TIME DEFAULT NULL, zondagVan TIME DEFAULT NULL, zondagTot TIME DEFAULT NULL, UNIQUE INDEX UNIQ_912C9E7A166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE dagbesteding_beschikbaarheid ADD CONSTRAINT FK_912C9E7A166D1F9C FOREIGN KEY (project_id) REFERENCES dagbesteding_projecten (id)');
        $this->addSql('ALTER TABLE dagbesteding_trajectcoaches DROP INDEX UNIQ_EA2465533D707F64, ADD INDEX IDX_C703865F3D707F64 (medewerker_id)');



    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('DROP TABLE dagbesteding_beschikbaarheid');
        $this->addSql('ALTER TABLE dagbesteding_trajectcoaches DROP INDEX IDX_C703865F3D707F64, ADD UNIQUE INDEX UNIQ_EA2465533D707F64 (medewerker_id)');



    }
}
