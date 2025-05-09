<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321131837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update foreign key in tw_huurovereenkomst_document and tw_huurovereenkomst_verslag';
    }

    public function up(Schema $schema): void
    {
        // Document
        $this->addSql('ALTER TABLE tw_huurovereenkomst_document DROP FOREIGN KEY FK_C5DF83BDC33F7837');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_document ADD CONSTRAINT FK_C5DF83BDC33F7837 FOREIGN KEY (document_id) REFERENCES tw_superdocumenten (id) ON UPDATE NO ACTION ON DELETE CASCADE');

        // Verslag
        $this->addSql('ALTER TABLE tw_huurovereenkomst_verslag DROP FOREIGN KEY FK_5F912B12D949475D');
        $this->addSql('ALTER TABLE tw_huurovereenkomst_verslag ADD CONSTRAINT FK_5F912B12D949475D FOREIGN KEY (verslag_id) REFERENCES tw_superverslagen (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
