<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210915085000 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

//        $this->addSql('ALTER TABLE tw_huurverzoeken DROP FOREIGN KEY FK_588F4E969E4835DA');
        $this->addSql('DROP INDEX IDX_B59AA1219E4835DA ON tw_huurverzoeken');
        $this->addSql('ALTER TABLE tw_huurverzoeken CHANGE huurder_id klant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tw_huurverzoeken ADD CONSTRAINT FK_B59AA1213C427B2F FOREIGN KEY (klant_id) REFERENCES tw_deelnemers (id)');
        $this->addSql('CREATE INDEX IDX_B59AA1213C427B2F ON tw_huurverzoeken (klant_id)');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tw_huurverzoeken DROP FOREIGN KEY FK_B59AA1213C427B2F');
        $this->addSql('DROP INDEX IDX_B59AA1213C427B2F ON tw_huurverzoeken');
        $this->addSql('ALTER TABLE tw_huurverzoeken CHANGE klant_id huurder_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tw_huurverzoeken ADD CONSTRAINT FK_588F4E969E4835DA FOREIGN KEY (huurder_id) REFERENCES tw_deelnemers (id)');
        $this->addSql('CREATE INDEX IDX_B59AA1219E4835DA ON tw_huurverzoeken (huurder_id)');

    }
}
