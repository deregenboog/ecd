<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210910085049 extends AbstractMigration
{
    public function up(Schema $schema) : void
        {
            // this up() migration is auto-generated, please modify it to your needs
            $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('RENAME TABLE `ecd`.`odp_superdocumenten` TO `ecd`.`tw_superdocumenten`');
        $this->addSql('RENAME TABLE `ecd`.`odp_coordinatoren` TO `ecd`.`tw_coordinatoren`');
        $this->addSql('RENAME TABLE `ecd`.`odp_deelnemers` TO `ecd`.`tw_deelnemers`');
        $this->addSql('RENAME TABLE `ecd`.`odp_deelnemer_verslag` TO `ecd`.`tw_deelnemer_verslag`');
        $this->addSql('RENAME TABLE `ecd`.`odp_deelnemer_document` TO `ecd`.`tw_deelnemer_document`');
        $this->addSql('RENAME TABLE `ecd`.`odp_huurders_odp_projecten` TO `ecd`.`tw_huurders_tw_projecten`');
        $this->addSql('RENAME TABLE `ecd`.`odp_vormvanovereenkomst` TO `ecd`.`tw_vormvanovereenkomst`');
        $this->addSql('RENAME TABLE `ecd`.`odp_superverslagen` TO `ecd`.`tw_superverslagen`');
        $this->addSql('RENAME TABLE `ecd`.`odp_locaties` TO `ecd`.`tw_locaties`');
        $this->addSql('RENAME TABLE `ecd`.`odp_afsluitingen` TO `ecd`.`tw_afsluitingen`');
        $this->addSql('RENAME TABLE `ecd`.`odp_huuraanbiedingen` TO `ecd`.`tw_huuraanbiedingen`');
        $this->addSql('RENAME TABLE `ecd`.`odp_huuraanbod_verslag` TO `ecd`.`tw_huuraanbod_verslag`');
        $this->addSql('RENAME TABLE `ecd`.`odp_huurverzoeken` TO `ecd`.`tw_huurverzoeken`');
        $this->addSql('RENAME TABLE `ecd`.`odp_huurverzoek_verslag` TO `ecd`.`tw_huurverzoek_verslag`');
        $this->addSql('RENAME TABLE `ecd`.`odp_huurverzoeken_odp_projecten` TO `ecd`.`tw_huurverzoeken_tw_projecten`');
        $this->addSql('RENAME TABLE `ecd`.`odp_huurovereenkomsten` TO `ecd`.`tw_huurovereenkomsten`');
        $this->addSql('RENAME TABLE `ecd`.`odp_huurovereenkomst_verslag` TO `ecd`.`tw_huurovereenkomst_verslag`');
        $this->addSql('RENAME TABLE `ecd`.`odp_huurovereenkomst_finverslag` TO `ecd`.`tw_huurovereenkomst_finverslag`');
        $this->addSql('RENAME TABLE `ecd`.`odp_huurovereenkomst_document` TO `ecd`.`tw_huurovereenkomst_document`');
        $this->addSql('RENAME TABLE `ecd`.`odp_huurovereenkomst_findocument` TO `ecd`.`tw_huurovereenkomst_findocument`');
        $this->addSql('RENAME TABLE `ecd`.`odp_woningbouwcorporaties` TO `ecd`.`tw_woningbouwcorporaties`');
        $this->addSql('RENAME TABLE `ecd`.`odp_projecten` TO `ecd`.`tw_projecten`');
        $this->addSql('RENAME TABLE `ecd`.`odp_duurthuisloos` TO `ecd`.`tw_duurthuisloos`');
        $this->addSql('RENAME TABLE `ecd`.`odp_afsluitredenen_vrijwilligers` TO `ecd`.`tw_afsluitredenen_vrijwilligers`');
        $this->addSql('RENAME TABLE `ecd`.`odp_deelnames` TO `ecd`.`tw_deelnames`');
        $this->addSql('RENAME TABLE `ecd`.`odp_vrijwilligers` TO `ecd`.`tw_vrijwilligers`');
        $this->addSql('RENAME TABLE `ecd`.`odp_vrijwilliger_locatie` TO `ecd`.`tw_vrijwilliger_locatie`');
        $this->addSql('RENAME TABLE `ecd`.`odp_vrijwilliger_memo` TO `ecd`.`tw_vrijwilliger_memo`');
        $this->addSql('RENAME TABLE `ecd`.`odp_vrijwilliger_document` TO `ecd`.`tw_vrijwilliger_document`');
        $this->addSql('RENAME TABLE `ecd`.`odp_intakes` TO `ecd`.`tw_intakes`');
        $this->addSql('RENAME TABLE `ecd`.`odp_training` TO `ecd`.`tw_training`');
        $this->addSql('RENAME TABLE `ecd`.`odp_werk` TO `ecd`.`tw_werk`');
        $this->addSql('RENAME TABLE `ecd`.`odp_huurbudget` TO `ecd`.`tw_huurbudget`');
        $this->addSql('RENAME TABLE `ecd`.`odp_binnen_via` TO `ecd`.`tw_binnen_via`');

        $this->addSql('ALTER TABLE `tw_huurverzoeken_tw_projecten` CHANGE `odp_huurverzoek_id` `tw_huurverzoek_id` INT(11) NOT NULL;');
        $this->addSql('ALTER TABLE `tw_huurverzoeken_tw_projecten` CHANGE `odp_project_id` `tw_project_id` INT(11) NOT NULL;');

        $this->addSql('ALTER TABLE `tw_huurders_tw_projecten` CHANGE `odp_huurder_id` `tw_huurder_id` INT(11) NOT NULL;');
        $this->addSql('ALTER TABLE `tw_huurders_tw_projecten` CHANGE `odp_project_id` `tw_project_id` INT(11) NOT NULL;');

        $this->addSql("UPDATE `tw_deelnemers` SET model = 'Klant' WHERE model = 'Huurder'");
        $this->addSql("UPDATE `tw_afsluitingen` SET discr = 'klant' WHERE discr = 'huurder'");


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    }
}
