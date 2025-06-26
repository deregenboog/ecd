-- VISITS
DROP TABLE IF EXISTS `tmp_visits`;
CREATE TABLE `tmp_visits` AS #// vervangen door create or replace. #IGNORE weggehaald om te zien wat er gebeurt via warnings en logs.
    SELECT
        `r`.`locatie_id`,
        `r`.`klant_id`,
        DATE(`r`.`binnen`) AS 'date',
        `g`.`volledig` AS 'gender',
        SUM(TIME_TO_SEC(TIMEDIFF(`buiten`, `binnen`))) AS 'duration'
    FROM `registraties` `r`
             INNER JOIN `klanten` `k` ON `r`.`klant_id` = `k`.`id`
             INNER JOIN `geslachten` `g` ON `k`.`geslacht_id` = `g`.`id`
             INNER JOIN `inloop_locatie_locatietype` AS ill ON r.locatie_id = ill.locatie_id
             INNER JOIN locatie_type lt ON ill.locatietype_id = lt.id AND lt.naam IN ('Inloop')
    WHERE YEAR(r.binnen) > '2020'
    GROUP BY `r`.`locatie_id`, `r`.`klant_id`, DATE(`r`.`binnen`), `g`.`volledig`
;
ALTER TABLE tmp_visits
    ADD INDEX idx_tmp_visits_locatie_id (locatie_id),
    ADD INDEX idx_tmp_visits_klant_id (klant_id),
    ADD INDEX idx_tmp_visits_date (`date`),
    ADD INDEX idx_tmp_visits_duration (`duration`),
    ADD INDEX idx_tmp_visits_gender (gender),
    ADD INDEX locatie_gender_date (locatie_id,gender,`date`),
    ADD INDEX gender (gender,`date`,klant_id),
    ADD INDEX idx_tmp_visits_date_gender (`date`,gender, klant_id)
;

-- OPEN DAYS
DROP TABLE IF EXISTS `tmp_open_days`;
CREATE TABLE `tmp_open_days` AS
    SELECT DISTINCT r.`locatie_id`, DATE(r.`binnen`) AS 'open_day'
    FROM `registraties` AS r
     INNER JOIN `inloop_locatie_locatietype` AS ill ON r.locatie_id = ill.locatie_id
     INNER JOIN locatie_type lt ON ill.locatietype_id = lt.id AND lt.naam IN ('Inloop')
    WHERE r.`locatie_id` != 0
    ORDER BY r.`locatie_id`
;
ALTER TABLE tmp_open_days
    ADD INDEX idx_tmp_open_days_locatie_id (locatie_id),
    ADD INDEX locatie_id (locatie_id,`open_day`),
    ADD INDEX idx_tmp_open_days_open_day (`open_day`)

;

-- VISITORS
DROP TABLE IF EXISTS `tmp_visitors`;
CREATE TABLE `tmp_visitors` AS
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
    INNER JOIN `inloop_locatie_locatietype` AS ill ON r.locatie_id = ill.locatie_id
    INNER JOIN locatie_type lt ON ill.locatietype_id = lt.id AND lt.naam IN ('Inloop')
WHERE YEAR(`binnen`) > 2020
;
ALTER TABLE tmp_visitors
    ADD INDEX idx_tmp_visitors_land_id (land_id),
    ADD INDEX idx_tmp_visitors_verslaving_id (verslaving_id),
    ADD INDEX idx_tmp_visitors_klant_id (klant_id),
    ADD INDEX idx_tmp_visitors_date (`date`),
    ADD INDEX idx_tmp_visitors_woonsituatie_id (`woonsituatie_id`),
    ADD INDEX idx_tmp_visitors_verblijfstatus_id (`verblijfstatus_id`),
    ADD INDEX idx_tmp_visitors_geslacht (geslacht),
    ADD KEY `verslaving_id` (`date`,`verslaving_id`,`geslacht`,`klant_id`),
    ADD KEY `verslaving_id_2` (`date`,`verslaving_id`,`klant_id`),
    ADD KEY `land_id` (`land_id`,`geslacht`,`date`,`klant_id`),
    ADD INDEX `idx_woonsituatie` (`woonsituatie_id`,`verblijfstatus_id`,`date`,`klant_id`)
;

-- AVERAGE DURATION
DROP TABLE IF EXISTS `tmp_avgduration`;
CREATE TABLE `tmp_avgduration` (label VARCHAR(64), range_start INT, range_end INT);
INSERT INTO tmp_avgduration VALUES
    ('Korter dan 10 minuten', 0, 600),
    ('10 tot 30 minuten', 600, 1800),
    ('30 minuten tot 2 uur', 1800, 7200),
    ('Langer dan 2 uur', 7200, 24*60*60*10)
;

DROP TABLE IF EXISTS `tmp_inloopdiensten`;
CREATE TABLE `tmp_inloopdiensten` IGNORE AS
SELECT
    `r`.`locatie_id`,
    DATE(`r`.`binnen`) AS 'date',
    `g`.`volledig` AS 'gender',
    r.activering,
    r.douche,
    r.kleding,
    r.maaltijd,
    r.veegploeg,
    `r`.`klant_id`,
    SUM(TIME_TO_SEC(TIMEDIFF(`buiten`, `binnen`))) AS 'duration'
FROM `registraties` `r`
         INNER JOIN `klanten` `k` ON `r`.`klant_id` = `k`.`id`
         INNER JOIN `geslachten` `g` ON `k`.`geslacht_id` = `g`.`id`
         INNER JOIN `inloop_locatie_locatietype` AS ill ON r.locatie_id = ill.locatie_id
         INNER JOIN locatie_type lt ON ill.locatietype_id = lt.id AND lt.naam IN ('Inloop')
WHERE YEAR(r.binnen) > '2020'
GROUP BY `r`.`locatie_id`, `r`.`klant_id`, `r`.`binnen`
;
ALTER TABLE tmp_inloopdiensten
    ADD INDEX idx_locatie_date (locatie_id,`date`),
    ADD INDEX idx_date (`date`)
;