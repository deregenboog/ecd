<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191008115751 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `werkgebieden` ADD `zichtbaar` BOOLEAN NOT NULL DEFAULT TRUE AFTER `naam`');


        $this->addSql('INSERT IGNORE INTO `werkgebieden` (`naam`,`zichtbaar`) VALUES
                                (\'Zaanstad - Assendelft\',0),
                                (\'Zaanstad - Kogerveldwijk\',0),
                                (\'Zaanstad - Krommenie\',0),
                                (\'Zaanstad - Nieuw West\',0),
                                (\'Zaanstad - Oud Koog a/d Zaan\',0),
                                (\'Zaanstad - Oud Zaandijk\',0),
                                (\'Zaanstad - Oude Haven\',0),
                                (\'Zaanstad - Pelders- en Hoornseveld\',0),
                                (\'Zaanstad - Poelenburg\',0),
                                (\'Zaanstad - Rooswijk\',0),
                                (\'Zaanstad - Rosmolenwijk\',0),
                                (\'Zaanstad - Westerkoog\',0),
                                (\'Zaanstad - Westzaan\',0),
                                (\'Zaanstad - Wormerveer\',0),
                                (\'Zaanstad - Zaandam Noord\',0),
                                (\'Zaanstad - Zaandam West\',0),
                                (\'Zaanstad - Zaandam Zuid\',0),
                                (\'Overig\',0)
                                ');

            $this->addSql(file_get_contents(__DIR__ . '/../data/zaanstad_postcodes.sql'));

        

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `werkgebieden` DROP `zichtbaar`');


        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad - Krommenie"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad - Assendelft"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad - WestZaan"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad - Nieuw-West"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad- Westerkoog"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad- Rooswijk"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad- Oud Zaandijk"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad- Oud Koog aan de Zaan"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad- Peldersveld"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad- Hoornseveld"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad- Poelenburg"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad- Wormerveer"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad- Zaandam Noord"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad- Zaandam West"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad- Zaandam Zuid"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad- Kogerveld"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad- Oudehaven"');
        $this->addSql('DELETE FROM werkgebieden WHERE naam = "Zaanstad- Rosmolenwijk"');
    }
}
