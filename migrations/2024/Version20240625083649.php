<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625083649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tw_huurovereenkomsten ADD vormVanOvereenkomst_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tw_huurovereenkomsten ADD CONSTRAINT FK_98F99DF6621E13D7 FOREIGN KEY (vormVanOvereenkomst_id) REFERENCES tw_vormvanovereenkomst (id)');

        $this->addSql('CREATE INDEX IDX_98F99DF6621E13D7 ON tw_huurovereenkomsten (vormVanOvereenkomst_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tw_huurovereenkomsten DROP FOREIGN KEY FK_98F99DF6621E13D7');
        $this->addSql('DROP INDEX IDX_98F99DF6621E13D7 ON tw_huurovereenkomsten');
        $this->addSql('ALTER TABLE tw_huurovereenkomsten DROP vormVanOvereenkomst_id');

    }
}
