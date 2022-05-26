<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220302132929 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dagbesteding_deelnames (id INT AUTO_INCREMENT NOT NULL, traject_id INT DEFAULT NULL, project_id INT DEFAULT NULL, active TINYINT(1) NOT NULL DEFAULT 1, INDEX IDX_328AD7035DFA57A1 (traject_id), INDEX IDX_328AD703166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE dagbesteding_deelnames ADD CONSTRAINT FK_328AD7035DFA57A1 FOREIGN KEY (traject_id) REFERENCES dagbesteding_trajecten (id)');
        $this->addSql('ALTER TABLE dagbesteding_deelnames ADD CONSTRAINT FK_328AD703166D1F9C FOREIGN KEY (project_id) REFERENCES dagbesteding_projecten (id)');


        $this->addSql('ALTER TABLE dagbesteding_beschikbaarheid DROP FOREIGN KEY FK_912C9E7A166D1F9C');
        $this->addSql('DROP INDEX UNIQ_912C9E7A166D1F9C ON dagbesteding_beschikbaarheid');
        $this->addSql('ALTER TABLE dagbesteding_beschikbaarheid CHANGE project_id deelname_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dagbesteding_beschikbaarheid ADD CONSTRAINT FK_912C9E7AC18FA9D5 FOREIGN KEY (deelname_id) REFERENCES dagbesteding_deelnames (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_912C9E7AC18FA9D5 ON dagbesteding_beschikbaarheid (deelname_id)');

        $this->addSql('INSERT INTO dagbesteding_deelnames (project_id, traject_id, active)

SELECT dp.id AS project_id, dt.id AS traject_id, 1 FROM
    dagbesteding_traject_project AS dtp
        INNER JOIN dagbesteding_projecten dp on dtp.project_id = dp.id
        INNER JOIN dagbesteding_trajecten dt on dtp.traject_id = dt.id
        INNER JOIN dagbesteding_deelnemers dd on dt.deelnemer_id = dd.id
;');
        $this->addSql('INSERT INTO dagbesteding_trajectsoorten (naam,active) VALUES ("SCIP",1)');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dagbesteding_beschikbaarheid DROP FOREIGN KEY FK_912C9E7AC18FA9D5');

        $this->addSql('DROP TABLE dagbesteding_deelnames');

        $this->addSql('DROP INDEX UNIQ_912C9E7AC18FA9D5 ON dagbesteding_beschikbaarheid');
        $this->addSql('ALTER TABLE dagbesteding_beschikbaarheid CHANGE deelname_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dagbesteding_beschikbaarheid ADD CONSTRAINT FK_912C9E7A166D1F9C FOREIGN KEY (project_id) REFERENCES dagbesteding_projecten (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_912C9E7A166D1F9C ON dagbesteding_beschikbaarheid (project_id)');

    }
}
