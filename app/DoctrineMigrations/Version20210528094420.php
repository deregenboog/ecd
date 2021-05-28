<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210528094420 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE opmerkingen ADD medewerker_id INT NOT NULL');
//        $this->addSql('ALTER TABLE opmerkingen ADD CONSTRAINT FK_C2C23B293D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_C2C23B293D707F64 ON opmerkingen (medewerker_id)');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


//        $this->addSql('ALTER TABLE opmerkingen DROP FOREIGN KEY FK_C2C23B293D707F64');
        $this->addSql('DROP INDEX IDX_C2C23B293D707F64 ON opmerkingen');
        $this->addSql('ALTER TABLE opmerkingen DROP medewerker_id');


    }
}
