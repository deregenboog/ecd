<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171219180644 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("INSERT IGNORE INTO werkgebieden (naam) VALUES ('Diemen')");
        foreach (range(1110, 1113) as $i) {
            foreach (range('A', 'Z') as $j) {
                foreach (range('A', 'Z') as $k) {
                    $postcode = $i.$j.$k;
                    $this->addSql("INSERT INTO postcodes (postcode, stadsdeel) VALUES ('{$postcode}', 'Diemen')");
                }
            }
        }

        $this->addSql("INSERT IGNORE INTO werkgebieden (naam) VALUES ('Amstelveen')");
        foreach (range(1180, 1189) as $i) {
            foreach (range('A', 'Z') as $j) {
                foreach (range('A', 'Z') as $k) {
                    $postcode = $i.$j.$k;
                    $this->addSql("INSERT INTO postcodes (postcode, stadsdeel) VALUES ('{$postcode}', 'Amstelveen')");
                }
            }
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
