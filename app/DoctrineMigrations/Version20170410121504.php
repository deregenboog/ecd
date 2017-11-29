<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170410121504 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("UPDATE nationaliteiten SET naam = 'Azerbajdsjan (burger van)' WHERE naam = 'Burger van Azerbajdsjan'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Bangladesh (burger van)' WHERE naam = 'Burger van Bangladesh'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Belarus (Wit-Rusland) (burger van)' WHERE naam = 'Burger van Belarus (Wit-Rusland)'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Britse afhankelijke gebieden (burger van)' WHERE naam = 'Burger van Britse afhankelijke gebieden'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Burkina Faso (burger van)' WHERE naam = 'Burger van Burkina Faso'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Duitse' WHERE naam = 'Burger van de Bondsrepubliek Duitsland'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Georgië (burger van)' WHERE naam = 'Burger van Georgië'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'India (burger van)' WHERE naam = 'Burger van India'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Kazachstan (burger van)' WHERE naam = 'Burger van Kazachstan'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Kroatië (burger van)' WHERE naam = 'Burger van Kroatië'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Mauritanië (burger van)' WHERE naam = 'Burger van Mauritanië'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Moldavië (burger van)' WHERE naam = 'Burger van Moldavië'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Niger (burger van)' WHERE naam = 'Burger van Niger'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Oekraïense' WHERE naam = 'Burger van Oekraine'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Russische' WHERE naam = 'Burger van Rusland'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Servische' WHERE naam = 'Burger van Servië'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Sloveense' WHERE naam = 'Burger van Slovenië'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Tadzjikistan (burger van)' WHERE naam = 'Burger van Tadzjikistan'");
        $this->addSql("UPDATE nationaliteiten SET naam = 'Trinidad en Tobago (burger van)' WHERE naam = 'Burger van Trinidad en Tobago'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
