<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200114115409 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pfo_clienten CHANGE telefoon_mobiel mobiel VARCHAR(255), CHANGE woonplaats plaats VARCHAR(255) ');
        $this->addSql('ALTER TABLE pfo_clienten ADD werkgebied VARCHAR(255) DEFAULT NULL, ADD postcodegebied VARCHAR(255) DEFAULT NULL');

        $this->addSql('ALTER TABLE pfo_clienten ADD CONSTRAINT FK_3C237EDD46708ED5 FOREIGN KEY (werkgebied) REFERENCES werkgebieden (naam)');
        $this->addSql('ALTER TABLE pfo_clienten ADD CONSTRAINT FK_3C237EDDFB02B9C2 FOREIGN KEY (postcodegebied) REFERENCES ggw_gebieden (naam)');

        $this->addSql('CREATE INDEX IDX_3C237EDD46708ED5 ON pfo_clienten (werkgebied)');
        $this->addSql('CREATE INDEX IDX_3C237EDDFB02B9C2 ON pfo_clienten (postcodegebied)');


    }

     public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE pfo_clienten DROP FOREIGN KEY FK_3C237EDD46708ED5');
        $this->addSql('ALTER TABLE pfo_clienten DROP FOREIGN KEY FK_3C237EDDFB02B9C2');
        $this->addSql('DROP INDEX IDX_3C237EDD46708ED5 ON pfo_clienten');
        $this->addSql('DROP INDEX IDX_3C237EDDFB02B9C2 ON pfo_clienten');

        $this->addSql('ALTER TABLE pfo_clienten CHANGE woonplaats plaats VARCHAR(255), CHANGE mobiel telefoon_mobiel VARCHAR(255), DROP werkgebied, DROP postcodegebied');

    }
}
