<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170406144536 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
      public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO postcodegebieden (postcodegebied, van, tot) VALUES ('Slotervaart', '1065', '1065');");
        $this->addSql("UPDATE postcodegebieden SET tot = '1062' WHERE van = '1062' AND tot = '1065';");
        $this->addSql("UPDATE postcodegebieden SET van = '1068', tot = '1069' WHERE van = '1067' AND tot = '1069';");
        $this->addSql("INSERT INTO postcodegebieden (postcodegebied, van, tot) VALUES ('Zuid', '1075', '1077');");
        $this->addSql("UPDATE postcodegebieden SET tot = '1071' WHERE van = '1071' AND tot = '1077';");
    }

    /**
     * @param Schema $schema
     */
     public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
