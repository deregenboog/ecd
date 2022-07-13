<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220616092524 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql("INSERT INTO `oekraine_locaties` (`naam`,`datum_van`) VALUES 
('Savoy',NOW()), 
('Botel',NOW()), 
('Oude Kleine Kapitein',NOW()),
('Carissima',NOW()),
('Via hotel Diemen',NOW()), 
('Riekerhof',NOW()), 
('Weesp',NOW()), 
('Beethoven hotel',NOW())");
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
