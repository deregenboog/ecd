<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220713121540 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dagbesteding_projecten ADD locatie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dagbesteding_projecten ADD CONSTRAINT FK_6AD94DA34947630C FOREIGN KEY (locatie_id) REFERENCES dagbesteding_locaties (id)');
        $this->addSql('CREATE INDEX IDX_6AD94DA34947630C ON dagbesteding_projecten (locatie_id)');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dagbesteding_projecten DROP FOREIGN KEY FK_6AD94DA34947630C');
        $this->addSql('DROP INDEX IDX_6AD94DA34947630C ON dagbesteding_projecten');
        $this->addSql('ALTER TABLE dagbesteding_projecten DROP locatie_id');
    }
}
