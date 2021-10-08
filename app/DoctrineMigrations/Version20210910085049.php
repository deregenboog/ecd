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

        $this->addSql('RENAME TABLE `odp_superdocumenten` TO `tw_superdocumenten`');
        $this->addSql('RENAME TABLE `odp_coordinatoren` TO `tw_coordinatoren`');
        $this->addSql('RENAME TABLE `odp_deelnemers` TO `tw_deelnemers`');
        $this->addSql('RENAME TABLE `odp_deelnemer_verslag` TO `tw_deelnemer_verslag`');
        $this->addSql('RENAME TABLE `odp_deelnemer_document` TO `tw_deelnemer_document`');
        $this->addSql('RENAME TABLE `odp_huurders_odp_projecten` TO `tw_huurders_tw_projecten`');
        $this->addSql('RENAME TABLE `odp_vormvanovereenkomst` TO `tw_vormvanovereenkomst`');
        $this->addSql('RENAME TABLE `odp_superverslagen` TO `tw_superverslagen`');
        $this->addSql('RENAME TABLE `odp_locaties` TO `tw_locaties`');
        $this->addSql('RENAME TABLE `odp_afsluitingen` TO `tw_afsluitingen`');
        $this->addSql('RENAME TABLE `odp_huuraanbiedingen` TO `tw_huuraanbiedingen`');
        $this->addSql('RENAME TABLE `odp_huuraanbod_verslag` TO `tw_huuraanbod_verslag`');
        $this->addSql('RENAME TABLE `odp_huurverzoeken` TO `tw_huurverzoeken`');
        $this->addSql('RENAME TABLE `odp_huurverzoek_verslag` TO `tw_huurverzoek_verslag`');
        $this->addSql('RENAME TABLE `odp_huurverzoeken_odp_projecten` TO `tw_huurverzoeken_tw_projecten`');
        $this->addSql('RENAME TABLE `odp_huurovereenkomsten` TO `tw_huurovereenkomsten`');
        $this->addSql('RENAME TABLE `odp_huurovereenkomst_verslag` TO `tw_huurovereenkomst_verslag`');
        $this->addSql('RENAME TABLE `odp_huurovereenkomst_finverslag` TO `tw_huurovereenkomst_finverslag`');
        $this->addSql('RENAME TABLE `odp_huurovereenkomst_document` TO `tw_huurovereenkomst_document`');
        $this->addSql('RENAME TABLE `odp_huurovereenkomst_findocument` TO `tw_huurovereenkomst_findocument`');
        $this->addSql('RENAME TABLE `odp_woningbouwcorporaties` TO `tw_woningbouwcorporaties`');
        $this->addSql('RENAME TABLE `odp_projecten` TO `tw_projecten`');
        $this->addSql('RENAME TABLE `odp_duurthuisloos` TO `tw_duurthuisloos`');
        $this->addSql('RENAME TABLE `odp_afsluitredenen_vrijwilligers` TO `tw_afsluitredenen_vrijwilligers`');
        $this->addSql('RENAME TABLE `odp_deelnames` TO `tw_deelnames`');
        $this->addSql('RENAME TABLE `odp_vrijwilligers` TO `tw_vrijwilligers`');
        $this->addSql('RENAME TABLE `odp_vrijwilliger_locatie` TO `tw_vrijwilliger_locatie`');
        $this->addSql('RENAME TABLE `odp_vrijwilliger_memo` TO `tw_vrijwilliger_memo`');
        $this->addSql('RENAME TABLE `odp_vrijwilliger_document` TO `tw_vrijwilliger_document`');
        $this->addSql('RENAME TABLE `odp_intakes` TO `tw_intakes`');
        $this->addSql('RENAME TABLE `odp_training` TO `tw_training`');
        $this->addSql('RENAME TABLE `odp_werk` TO `tw_werk`');
        $this->addSql('RENAME TABLE `odp_huurbudget` TO `tw_huurbudget`');
        $this->addSql('RENAME TABLE `odp_binnen_via` TO `tw_binnen_via`');

        $this->addSql('ALTER TABLE `tw_huurverzoeken_tw_projecten` CHANGE `odp_huurverzoek_id` `tw_huurverzoek_id` INT(11) NOT NULL;');
        $this->addSql('ALTER TABLE `tw_huurverzoeken_tw_projecten` CHANGE `odp_project_id` `tw_project_id` INT(11) NOT NULL;');

        $this->addSql('ALTER TABLE `tw_huurders_tw_projecten` CHANGE `odp_huurder_id` `tw_huurder_id` INT(11) NOT NULL;');
        $this->addSql('ALTER TABLE `tw_huurders_tw_projecten` CHANGE `odp_project_id` `tw_project_id` INT(11) NOT NULL;');

        $this->addSql("UPDATE `tw_deelnemers` SET model = 'Klant' WHERE model = 'Huurder'");
        $this->addSql("UPDATE `tw_afsluitingen` SET discr = 'klant' WHERE discr = 'huurder'");

            $this->addSql("UPDATE `zrm_reports` SET request_module = 'TwHuurder' WHERE request_module = 'OdpHuurder'");


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    }
}
