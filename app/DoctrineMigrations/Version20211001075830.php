<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211001075830 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tw_alcohol (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE tw_deelnemers ADD alcohol_id INT DEFAULT NULL, ADD shortlist_id INT DEFAULT NULL, ADD inkomensverklaring VARCHAR(255) DEFAULT NULL,ADD toelichtingInkomen VARCHAR(255) DEFAULT NULL, DROP inschrijvingWoningnet, CHANGE intakeStatus_id intakeStatus_id INT NOT NULL');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E43172565357D7EE FOREIGN KEY (alcohol_id) REFERENCES tw_alcohol (id)');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E4317256452FF74C FOREIGN KEY (inschrijvingWoningnet_id) REFERENCES tw_inschrijvingwoningnet (id)');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E4317256B4770027 FOREIGN KEY (shortlist_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_E43172565357D7EE ON tw_deelnemers (alcohol_id)');
        $this->addSql('CREATE INDEX IDX_E4317256452FF74C ON tw_deelnemers (inschrijvingWoningnet_id)');
        $this->addSql('CREATE INDEX IDX_E4317256B4770027 ON tw_deelnemers (shortlist_id)');
        $this->addSql("INSERT INTO tw_alcohol (label) VALUES ('Regelmatig'),('Soms'),('Nooit'),('Tegen alcohol')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E43172565357D7EE');
        $this->addSql('DROP TABLE tw_alcohol');


        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E4317256452FF74C');
        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E4317256B4770027');
        $this->addSql('DROP INDEX IDX_E43172565357D7EE ON tw_deelnemers');
        $this->addSql('DROP INDEX IDX_E4317256452FF74C ON tw_deelnemers');
        $this->addSql('DROP INDEX IDX_E4317256B4770027 ON tw_deelnemers');
        $this->addSql('ALTER TABLE tw_deelnemers ADD inschrijvingWoningnet TINYINT(1) DEFAULT NULL, DROP toelichtingInkomen, DROP alcohol_id, DROP shortlist_id, DROP inkomensverklaring, CHANGE project_id project_id INT DEFAULT NULL, CHANGE intakeStatus_id intakeStatus_id INT DEFAULT 1 NOT NULL');


    }
}
