<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310090908 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dagbesteding_werkdoelen (id INT AUTO_INCREMENT NOT NULL, deelnemer_id INT DEFAULT NULL, traject_id INT DEFAULT NULL, medewerker_id INT DEFAULT NULL, tekst LONGTEXT DEFAULT NULL, datum DATE NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_56257F585DFA57A1 (deelnemer_id), INDEX IDX_56257F58A0CADD4 (traject_id), INDEX IDX_56257F583D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE dagbesteding_werkdoelen ADD CONSTRAINT FK_56257F585DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES dagbesteding_deelnemers (id)');
        $this->addSql('ALTER TABLE dagbesteding_werkdoelen ADD CONSTRAINT FK_56257F58A0CADD4 FOREIGN KEY (traject_id) REFERENCES dagbesteding_trajecten (id)');
        $this->addSql('ALTER TABLE dagbesteding_werkdoelen ADD CONSTRAINT FK_56257F583D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');

    }

     public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('DROP TABLE dagbesteding_werkdoelen');

    }
}
