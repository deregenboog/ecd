<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214132028 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add talen table to database.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE talen (id INT AUTO_INCREMENT NOT NULL, favoriet TINYINT(1) NOT NULL, afkorting VARCHAR(255) NOT NULL, naam VARCHAR(255) NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql("
                INSERT INTO `talen` (`naam`, `favoriet`, `afkorting`) VALUES
                ('Nederlands', 1, ''),
                ('Engels', 1, ''),
                ('Onbekend', 0, ''),
                ( 'Slowaaks', 0, ''),
                ( 'Tsjechisch', 0, ''),
                ( 'Georgisch', 0, ''),
                ( 'Tadzjikistan', 0, ''),
                ( 'Oekraïens', 1, ''),
                ( 'Moldavisch', 0, ''),
                ( 'Kazachstan', 0, ''),
                ( 'Belarus (Wit-Rusland)', 0, ''),
                ( 'Azerbajdsjan', 0, ''),
                ( 'Russisch', 1, ''),
                ( 'Sloveens', 0, ''),
                ( 'Kroatisch', 0, ''),
                ( 'Lets', 0, ''),
                ( 'Estnisch', 0, ''),
                ( 'Litouws', 0, ''),
                ( 'Albanees', 0, ''),
                ( 'Bulgaars', 1, ''),
                ( 'Deens', 0, ''),
                ( 'Duits', 1, ''),
                ( 'Fins', 0, ''),
                ( 'Frans', 1, ''),
                ( 'Jemenitisch', 0, ''),
                ( 'Grieks', 0, ''),
                ( 'Hongaars', 0, ''),
                ( 'Iers', 0, ''),
                ( 'IJslands', 0, ''),
                ( 'Italiaans', 0, ''),
                ( 'Joegoslavisch', 0, ''),
                ( 'Luxemburgs', 0, ''),
                ( 'Maltees', 0, ''),
                ( 'Noors', 0, ''),
                ( 'Oostenrijks', 0, ''),
                ( 'Pools', 1, ''),
                ( 'Portugees', 0, ''),
                ( 'Roemeens', 1, ''),
                ( 'Spaans', 1, ''),
                ( 'Zweeds', 0, ''),
                ( 'Zwitsers', 0, ''),
                ( 'Eritres', 0, ''),
                ( 'Macedonisch', 0, ''),
                ('Algerijns', 0, ''),
                ('Angolees', 0, ''),
                ('Burundisch', 0, ''),
                ('Burkina Faso', 0, ''),
                ('Centrafrikaans', 0, ''),
                ('Kongolees', 0, ''),
                ('Egyptisch', 0, ''),
                ('Etiopisch', 0, ''),
                ('Gambiaans', 0, ''),
                ('Ghanees', 0, ''),
                ('Guinees', 0, ''),
                ('Ivoriaans', 0, ''),
                ('Kameroens', 0, ''),
                ('Kenyaans', 0, ''),
                ('Liberiaans', 0, ''),
                ('Libisch', 0, ''),
                ('Malinees', 0, ''),
                ('Marokkaans', 0, ''),
                ('Mauritanisch', 0, ''),
                ('Niger', 0, ''),
                ('Nigeriaans', 0, ''),
                ('Oegandees', 0, ''),
                ('Guineebissaus', 0, ''),
                ('Zuidafrikaans', 0, ''),
                ('Senegalees', 0, ''),
                ('Sierraleoons', 0, ''),
                ('Soedanees', 0, ''),
                ('Somalisch', 0, ''),
                ('Tanzaniaans', 0, ''),
                ('Togolees', 0, ''),
                ('Tunesisch', 0, ''),
                ('Canadees', 0, ''),
                ('Cubaans', 0, ''),
                ('Jamaicaans', 0, ''),
                ('Mexicaans', 0, ''),
                ('Nicaraguaans', 0, ''),
                ('Argentijns', 0, ''),
                ('Braziliaans', 0, ''),
                ('Chileens', 0, ''),
                ('Colombiaans', 0, ''),
                ('Ecuadoraans', 0, ''),
                ('Guyaans', 0, ''),
                ('Peruaans', 0, ''),
                ('Surinaams', 0, ''),
                ('Venezolaans', 0, ''),
                ('Afghaans', 0, ''),
                ('Bhutaans', 0, ''),
                ('Burmaans', 0, ''),
                ('Srilankaans', 0, ''),
                ('Chinees', 0, ''),
                ('Cyprisch', 0, ''),
                ('India', 0, ''),
                ('Indonesisch', 0, ''),
                ('Iraaks', 0, ''),
                ('Iraans', 0, ''),
                ('Israëlisch', 0, ''),
                ('Japans', 0, ''),
                ('Laotiaans', 0, ''),
                ('Libanees', 0, ''),
                ('Maldivisch', 0, ''),
                ('Mongolisch', 0, ''),
                ('Nepalees', 0, ''),
                ('Pakistaans', 0, ''),
                ('Singaporaans', 0, ''),
                ('Syrisch', 0, ''),
                ('Turks', 0, ''),
                ('Zuidkoreaans', 0, ''),
                ('Bangladesh', 0, ''),
                ('Australisch', 0, ''),
                ('Nieuwzeelands', 0, ''),
                ('Westsamoaans', 0, ''),
                ('Amerikaans', 0, ''),
                ('Servisch', 0, ''),
                ('Overig', 0, ''),
                ('Indiaas', 0, ''),
                ('Trinidad en Tobago', 0, ''),
                ('Vietnamees', 0, ''),
                ('Bosnier', 0, ''),
                ('Thais', 0, ''),
                ('Armeens', 0, ''),
                ('Turkmeens', 0, ''),
                ('Tadzjieks', 0, ''),
                ('Thais', 0, ''),
                ('Myanmarees', 0, '');
");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE talen');
    }
}
