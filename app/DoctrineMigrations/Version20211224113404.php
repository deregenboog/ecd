<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211224113404 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('CREATE TABLE tw_huurovereenkomst_type (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL DEFAULT NOW(), modified DATETIME NOT NULL DEFAULT NOW(), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE tw_huurovereenkomsten ADD huurovereenkomstType_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tw_huurovereenkomsten ADD CONSTRAINT FK_98F99DF67F442D FOREIGN KEY (huurovereenkomstType_id) REFERENCES tw_huurovereenkomst_type (id)');
        $this->addSql('CREATE INDEX IDX_98F99DF67F442D ON tw_huurovereenkomsten (huurovereenkomstType_id)');

        $this->addSql("INSERT INTO tw_huurovereenkomst_type (naam, `active`) VALUES ('Los',1), ('Duo',1),('Onbekend',1)");
    }

     public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tw_huurovereenkomsten DROP FOREIGN KEY FK_98F99DF67F442D');
        $this->addSql('DROP TABLE tw_huurovereenkomst_type');
        $this->addSql('DROP INDEX IDX_98F99DF67F442D ON tw_huurovereenkomsten');
        $this->addSql('ALTER TABLE tw_huurovereenkomsten DROP huurovereenkomstType_id');

    }
}
