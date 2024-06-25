<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625083918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT IGNORE INTO tw_vormvanovereenkomst (label, startdate)
                            SELECT DISTINCT vorm, '2021-07-01' FROM tw_huurovereenkomsten");

        $this->addSql("UPDATE tw_huurovereenkomsten ho RIGHT JOIN tw_vormvanovereenkomst vvo
                            ON vvo.label = ho.vorm
                            SET ho.vormVanOvereenkomst_id = vvo.id;");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
