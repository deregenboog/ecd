<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230623100537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'adding specifiek locaties to intakes';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('CREATE TABLE locaties_accessintakes (intake_id INT NOT NULL, locatie_id INT NOT NULL, INDEX IDX_466C3B7F733DE450 (intake_id), INDEX IDX_466C3B7F4947630C (locatie_id), PRIMARY KEY(intake_id, locatie_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE locaties_accessintakes ADD CONSTRAINT FK_466C3B7F733DE450 FOREIGN KEY (intake_id) REFERENCES intakes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE locaties_accessintakes ADD CONSTRAINT FK_466C3B7F4947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intakes ADD specifiek_toegang_van DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE intakes ADD toegangIntakelocatie tinyint(1) DEFAULT NULL');

    }

    public function down(Schema $schema): void
    {

        $this->addSql('ALTER TABLE locaties_accessintakes DROP FOREIGN KEY FK_466C3B7F733DE450');
        $this->addSql('ALTER TABLE locaties_accessintakes DROP FOREIGN KEY FK_466C3B7F4947630C');
        $this->addSql('DROP TABLE locaties_accessintakes');
        $this->addSql('ALTER TABLE intakes DROP specifiek_toegang_van');
        $this->addSql('ALTER TABLE intakes ADD toegangIntakelocatie tinyint(1) DEFAULT NULL');

    }
}
