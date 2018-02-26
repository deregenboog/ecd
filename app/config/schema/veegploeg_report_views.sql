-- VISITS
DROP TABLE IF EXISTS `tmp_visits_veegploeg`;
CREATE TABLE `tmp_visits_veegploeg` AS
    SELECT
        `r`.`locatie_id`,
        `r`.`klant_id`,
        DATE(`r`.`binnen`) AS 'date',
        `g`.`volledig` AS 'gender',
        SUM(TIME_TO_SEC(TIMEDIFF(`buiten`, `binnen`))) AS 'duration'
    FROM `registraties` `r`
    INNER JOIN `klanten` `k` ON `r`.`klant_id` = `k`.`id`
    INNER JOIN `geslachten` `g` ON `k`.`geslacht_id` = `g`.`id`
    WHERE `r`.`veegploeg` = 1
    GROUP BY `r`.`locatie_id`, `r`.`klant_id`, DATE(`r`.`binnen`), `g`.`volledig`
;
ALTER TABLE tmp_visits_veegploeg
    ADD INDEX idx_tmp_visits_veegploeg_locatie_id (locatie_id),
    ADD INDEX idx_tmp_visits_veegploeg_klant_id (klant_id),
    ADD INDEX idx_tmp_visits_veegploeg_date (`date`),
    ADD INDEX idx_tmp_visits_veegploeg_duration (`duration`),
    ADD INDEX idx_tmp_visits_veegploeg_gender (gender)
;

-- VISITORS
DROP TABLE IF EXISTS `tmp_visitors_veegploeg`;
CREATE TABLE `tmp_visitors_veegploeg` AS
    SELECT DISTINCT
        `r`.`klant_id`,
        `k`.`land_id`,
        `g`.`volledig` AS `geslacht`,
        DATE(`binnen`) AS `date`,
        `v`.`id` AS 'verslaving_id',
        i.woonsituatie_id,
        i.verblijfstatus_id
    FROM `registraties` `r`
    INNER JOIN `klanten` `k` ON `r`.`klant_id` = `k`.`id`
    INNER JOIN `geslachten` `g` ON `k`.`geslacht_id` = `g`.`id`
    LEFT JOIN `landen` `l` ON `k`.`land_id` = `l`.`id`
    LEFT JOIN `intakes` `i` ON `k`.`laste_intake_id` = `i`.`id`
    LEFT JOIN `verslavingen` `v` ON `i`.`primaireproblematiek_id` = `v`.`id`
    WHERE `r`.`veegploeg` = 1
;
ALTER TABLE tmp_visitors_veegploeg
    ADD INDEX idx_tmp_visitors_veegploeg_land_id (land_id),
    ADD INDEX idx_tmp_visitors_veegploeg_verslaving_id (verslaving_id),
    ADD INDEX idx_tmp_visitors_veegploeg_klant_id (klant_id),
    ADD INDEX idx_tmp_visitors_veegploeg_date (`date`),
    ADD INDEX idx_tmp_visitors_veegploeg_woonsituatie_id (`woonsituatie_id`),
    ADD INDEX idx_tmp_visitors_veegploeg_verblijfstatus_id (`verblijfstatus_id`),
    ADD INDEX idx_tmp_visitors_veegploeg_geslacht (geslacht)
;
