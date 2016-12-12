-- VISITS
DROP TABLE IF EXISTS `tmp_visits`;
CREATE TABLE `tmp_visits` AS
SELECT 
	`r`.`locatie_id`,
	`r`.`klant_id`,
	DATE(`r`.`binnen`) AS 'date',
	`g`.`volledig` AS 'gender',
	sum(TIME_TO_SEC(TIMEDIFF(`buiten`, `binnen`))) AS 'duration'
FROM `registraties` `r`
INNER
	JOIN `klanten` `k`
	ON `r`.`klant_id` = `k`.`id`
INNER
	JOIN `geslachten` `g`
	ON `k`.`geslacht_id` = `g`.`id`
group by 
	`r`.`locatie_id`,
	`r`.`klant_id`,
	DATE(`r`.`binnen`),
	`g`.`volledig`;

ALTER TABLE tmp_visits
     ADD INDEX idx_tmp_visits_locatie_id (locatie_id),
     ADD INDEX idx_tmp_visits_klant_id (klant_id),
     ADD INDEX idx_tmp_visits_date (`date`),
     ADD INDEX idx_tmp_visits_duration (`duration`),
     ADD INDEX idx_tmp_visits_gender (gender);

-- OPEN DAYS

DROP TABLE IF EXISTS `tmp_open_days`;
CREATE TABLE `tmp_open_days` AS
SELECT DISTINCT
	`locatie_id`,
	DATE(`binnen`) AS 'open_day'
FROM `registraties`
WHERE
	`locatie_id` != 0
ORDER BY `locatie_id`
;

ALTER TABLE tmp_open_days
     ADD INDEX idx_tmp_open_days_locatie_id (locatie_id),
     ADD INDEX idx_tmp_open_days_open_day (`open_day`);

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
INNER
	JOIN `klanten` `k`
	ON `r`.`klant_id` = `k`.`id`
INNER
	JOIN `geslachten` `g`
	ON `k`.`geslacht_id` = `g`.`id`
LEFT
	JOIN `landen` `l`
	ON `k`.`land_id` = `l`.`id`
LEFT
	JOIN `intakes` `i`
	ON `k`.`laste_intake_id` = `i`.`id`
LEFT
	JOIN `verslavingen` `v`
	ON `i`.`primaireproblematiek_id` = `v`.`id`
;

ALTER TABLE tmp_visitors
     ADD INDEX idx_tmp_visitors_land_id (land_id),
     ADD INDEX idx_tmp_visitors_verslaving_id (verslaving_id),
     ADD INDEX idx_tmp_visitors_klant_id (klant_id),
     ADD INDEX idx_tmp_visitors_date (`date`),
     ADD INDEX idx_tmp_visitors_woonsituatie_id (`woonsituatie_id`),
     ADD INDEX idx_tmp_visitors_verblijfstatus_id (`verblijfstatus_id`),
     ADD INDEX idx_tmp_visitors_geslacht (geslacht);

-- AVERAGE DURATION

DROP TABLE IF EXISTS `tmp_avgduration`;
CREATE TABLE `tmp_avgduration` (label varchar(64), range_start int, range_end int);
insert into tmp_avgduration values
    ('Korter dan 10 minuten', 0, 600),
    ('10 tot 30 minuten', 600, 1800),
    ('30 minuten tot 2 uur', 1800, 7200),
    ('Langer dan 2 uur', 7200, 24*60*60*10)
;
