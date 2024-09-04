<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240830121725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE oekraine_bezoekers ADD mentalCoach_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE oekraine_bezoekers ADD CONSTRAINT FK_7027BCA152EE87CF FOREIGN KEY (mentalCoach_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_7027BCA152EE87CF ON oekraine_bezoekers (mentalCoach_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE oekraine_bezoekers DROP FOREIGN KEY FK_7027BCA152EE87CF');
        $this->addSql('DROP INDEX IDX_7027BCA152EE87CF ON oekraine_bezoekers');
        $this->addSql('ALTER TABLE oekraine_bezoekers DROP mentalCoach_id');


    }
}
