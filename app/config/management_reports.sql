-- START 1-4.Number of unique visitors in a period of time split on gender and location
-- HEAD: Bezoekers per dag openstelling
-- FIELDS: a.naam - Locatie; a.Dagen geopend - Dagen geopend; a.Mannen uniek - Mannen uniek; a.Vrouwen uniek - Vrouwen uniek; a.Totaal uniek - Totaal uniek; a.Mannen per dag - Mannen per dag; a.Vrouwen per dag - Vrouwen per dag; a.Totaal per dag - Totaal per dag
-- ARRAY
-- !DISABLE
-- SUMMARY
SELECT *
  FROM (
(SELECT
	`naam`,
	cast(IFNULL(`open_days`, 0) as UNSIGNED) AS 'Dagen geopend',
	(SELECT COUNT(DISTINCT `klant_id`) FROM `tmp_visits` WHERE `locatie_id` = `l`.`id` AND `gender` = 'Man' AND `date` between :from and :until) AS 'Mannen uniek',
	(SELECT COUNT(DISTINCT `klant_id`) FROM `tmp_visits` WHERE `locatie_id` = `l`.`id` AND `gender` = 'Vrouw' AND `date` between :from and :until) AS 'Vrouwen uniek',
	(SELECT COUNT(DISTINCT `klant_id`) FROM `tmp_visits` WHERE `locatie_id` = `l`.`id` AND `date` between :from and :until) AS 'Totaal uniek',
    -- There are three ways to compute 'number of men per day'. The worst one is probably dividing the previous count by the total of days. It is bad because the uniqueness of each person doesn't reflect that Peter came everyday.
    -- The second one, in between, is just dividing all men registrations over the total days. It is not perfect, because if Peter came twice one day, it will alter the statistics a little bit, but still is reasonably good figure if most men visitors register only once per day.
    -- We go for the third possibility, very intensive: to compute unique men EVERY day, and then average that. It is intensive because it requires going through the registrations day by day, finding unique men for each. This is done in a tmp table, every night, by cron.
	IFNULL(ROUND(((SELECT COUNT(DISTINCT klant_id, date) FROM `tmp_visits` WHERE `locatie_id` = `l`.`id` AND `gender` = 'Man' AND `date` between :from and :until) / `o`.`open_days`), 1), 0) AS 'Mannen per dag',
	IFNULL(ROUND(((SELECT COUNT(DISTINCT klant_id, date) FROM `tmp_visits` WHERE `locatie_id` = `l`.`id` AND `gender` = 'Vrouw' AND `date` between :from and :until) / `o`.`open_days`), 1), 0) AS 'Vrouwen per dag',
	IFNULL(ROUND(((SELECT COUNT(DISTINCT klant_id, date) FROM `tmp_visits` WHERE `locatie_id` = `l`.`id` AND `date` between :from and :until) / `o`.`open_days`), 1), 0) AS 'Totaal per dag',
    naam as order_naam
FROM `locaties` `l`
LEFT
	JOIN (SELECT count(*) AS open_days, locatie_id
            FROM tmp_open_days
           WHERE `open_day` between :from and :until
           GROUP BY tmp_open_days.locatie_id) o
	ON `l`.`id` = `o`.`locatie_id`
ORDER BY `naam`)
UNION
(SELECT
	'Alle locaties samen',
	CAST(AVG(`o`.`open_days`) AS UNSIGNED),
	(SELECT COUNT(DISTINCT `klant_id`) FROM `tmp_visits` WHERE `gender` = 'Man' AND `date` between :from and :until),
	(SELECT COUNT(DISTINCT `klant_id`) FROM `tmp_visits` WHERE `gender` = 'Vrouw' AND `date` between :from and :until),
	(SELECT COUNT(DISTINCT `klant_id`) FROM `tmp_visits` WHERE `date` between :from and :until),
	IFNULL(ROUND(((SELECT COUNT(DISTINCT klant_id, date) FROM `tmp_visits` WHERE `gender` = 'Man' AND `date` between :from and :until) / `o`.`open_days`), 1), 0),
	IFNULL(ROUND(((SELECT COUNT(DISTINCT klant_id, date) FROM `tmp_visits` WHERE `gender` = 'Vrouw' AND `date` between :from and :until) / `o`.`open_days`), 1), 0),
	IFNULL(ROUND(((SELECT COUNT(DISTINCT klant_id, date) FROM `tmp_visits` WHERE  `date` between :from and :until) / `o`.`open_days`), 1), 0),
    'zzzzzzzzzz' as order_naam
FROM
 	(SELECT count(*) AS open_days, locatie_id
       FROM tmp_open_days
      WHERE `open_day` between :from and :until
      GROUP BY tmp_open_days.locatie_id) o
GROUP BY 1
)) a
order by order_naam
;

-- START 5-6 - Woonsituatie
-- HEAD: Woonsituatie van bezoekers
-- FIELDS: 0.Woonsituatie - Woonsituatie; 0.Aantal - Aantal; 0.percentage - Percentage
-- !DISABLE
-- ARRAY

select Coalesce(w.naam, 'Onbekend') Woonsituatie,
       COUNT(*) Aantal,
       Concat(Round(COUNT(*) / (SELECT COUNT(DISTINCT klant_id)
                                  FROM tmp_visits v
                                 WHERE date between :from and :until
			) * 100), '%') percentage
  from (
		select distinct klant_id, woonsituatie_id
           from tmp_visitors v
		 where date between :from and :until
       ) v
LEFT JOIN woonsituaties w
  ON v.woonsituatie_id = w.id
group by 1
order by Aantal desc

;

-- START 7-8-9-10-11-12. Bezoekers dakloos of thuisloos, met of zonder regiobinding
-- HEAD: Regiobinding en woonsituatie van bezoekers
-- FIELDS: 0.naam - Bezoekers; 0.Met regiobinding - Met regiobinding; 0.Zonder regiobinding - Zonder regiobinding; 0.Regiobinding onbekend - Regiobinding onbekend; 0.Totaal - Totaal
-- ARRAY
-- !DISABLE

(SELECT
	'Dakloos' AS 'naam',
	(SELECT COUNT(distinct klant_id)
	FROM `tmp_visitors` `tv`
	WHERE
		`woonsituatie_id` IN (97) AND
		`verblijfstatus_id` IN (1, 2) AND
        `date` between :from and :until) AS 'Met regiobinding',

	(SELECT COUNT(distinct klant_id)
	FROM `tmp_visitors` `tv`
	WHERE
		`woonsituatie_id` IN (97) AND
		`verblijfstatus_id` IN (7, 4, 6, 7)
        AND `date` between :from and :until
    ) AS 'Zonder regiobinding',
	(SELECT COUNT(distinct klant_id)
	FROM `tmp_visitors` `tv`
	WHERE
		`woonsituatie_id` IN (97) AND
		`verblijfstatus_id` IS NULL
        AND `date` between :from and :until) AS 'Regiobinding onbekend',
	(SELECT COUNT(distinct klant_id)
	FROM `tmp_visitors` `tv`
	WHERE
		`woonsituatie_id` IN (97) AND
		(`verblijfstatus_id` IN (1, 2, 7, 4, 6, 7) or `verblijfstatus_id` is null)
        AND `date` between :from and :until) AS 'Totaal')
UNION
(SELECT
	'Thuisloos',
	(
	SELECT COUNT(distinct klant_id)
	FROM `tmp_visitors` `tv`
	WHERE
		`woonsituatie_id` IN (10, 14, 19, 11, 13, 16, 12, 15, 17) AND
		`verblijfstatus_id` IN (1, 2) AND `date` between :from and :until
),
	(
	SELECT COUNT(distinct klant_id)
	FROM `tmp_visitors` `tv`
	WHERE
		`woonsituatie_id` IN (10, 14, 19, 11, 13, 16, 12, 15, 17) AND
		`verblijfstatus_id` IN (3, 4, 6) AND `date` between :from and :until
),
	(
	SELECT COUNT(distinct klant_id)
	FROM `tmp_visitors` `tv`
	WHERE
		`woonsituatie_id` IN (10, 14, 19, 11, 13, 16, 12, 15, 17) AND
		`verblijfstatus_id` IS NULL AND `date` between :from and :until
),
	(
	SELECT COUNT(distinct klant_id)
	FROM `tmp_visitors` `tv`
	WHERE
		`woonsituatie_id` IN (10, 14, 19, 11, 13, 16, 12, 15, 17) AND
		(`verblijfstatus_id` IN (1, 2, 3, 4, 6)  or `verblijfstatus_id` is null)
    AND `date` between :from and :until
)
)
UNION
(SELECT
	'Woonsituatie onbekend',
	(
	SELECT COUNT(distinct klant_id)
	FROM `tmp_visitors` `tv`
	WHERE
		`woonsituatie_id` IS NULL AND
		`verblijfstatus_id` IN (1, 2) AND `date` between :from and :until
),
	(
	SELECT COUNT(distinct klant_id)
	FROM `tmp_visitors` `tv`
	WHERE
		`woonsituatie_id` IS NULL AND
		`verblijfstatus_id` IN (3, 4, 6, 7) AND `date` between :from and :until
),
	(
	SELECT COUNT(distinct klant_id)
	FROM `tmp_visitors` `tv`
	WHERE
		`woonsituatie_id` IS NULL AND
		`verblijfstatus_id` IS NULL AND `date` between :from and :until
),
	(
	SELECT COUNT(distinct klant_id)
	FROM `tmp_visitors` `tv`
	WHERE
		`woonsituatie_id` IS NULL AND
		(`verblijfstatus_id` IN (1, 2, 3, 4, 6, 7)  or `verblijfstatus_id` is null)
     AND `date` between :from and :until
))
;

-- START 13. Geboortelanden
-- HEAD: Geboortelanden van bezoekers
-- FIELDS: l.Geboorteland - Geboorteland; 0.Aantal mannen - Aantal mannen; 0.Aantal vrouwen - Aantal vrouwen; 0.Totaal - Totaal
-- ARRAY
-- !DISABLE

SELECT
	`l`.`land` AS 'Geboorteland',
	(SELECT COUNT(distinct klant_id) FROM `tmp_visitors` `tv` WHERE `tv`.`land_id` = `l`.`id` AND `tv`.`geslacht` = 'Man' AND `date` between :from and :until) AS 'Aantal mannen',
	(SELECT COUNT(distinct klant_id) FROM `tmp_visitors` `tv` WHERE `tv`.`land_id` = `l`.`id` AND `tv`.`geslacht` = 'Vrouw' AND `date` between :from and :until) AS 'Aantal vrouwen',
	(SELECT COUNT(distinct klant_id) FROM `tmp_visitors` `tv` WHERE `tv`.`land_id` = `l`.`id` AND `date` between :from and :until) AS 'Totaal'
FROM `landen` `l`
ORDER BY 4 DESC
LIMIT 10
;

-- START 14-15-16. - Douches, maaltijden en dagdelen.sql
-- Number of douches/kleding/maaltijd/activeringen per location per period
-- HEAD: Douches, maaltijden, dagdelen, kleding en veegploeg
-- FIELDS: a.naam - Locatie; a.Aantal douches - Aantal douches; a.Aantal maaltijden - Aantal maaltijden; a.Aantal activeringen - Aantal activeringen; a.Aantal unieke activeringen - Aantal personen activering; a.Aantal kleding - Aantal kleding; a.Aantal veegploeg - Aantal veegploeg; a.Aantal personen veegploeg - Aantal personen veegploeg
-- ARRAY
-- SUMMARY
-- !DISABLE
SELECT * FROM (
    (SELECT
        `naam`,
        IFNULL((SELECT SUM(IF(`douche` = -1, 1, 0)) FROM `registraties` `tr` WHERE `tr`.`locatie_id` = `tl`.`id` AND `binnen` between :from and :until), 0) AS 'Aantal douches',
        IFNULL((SELECT SUM(`maaltijd`) FROM `registraties` `tr` WHERE `tr`.`locatie_id` = `tl`.`id` AND `binnen` between :from and :until), 0) AS 'Aantal maaltijden',
        IFNULL((SELECT SUM(`activering`) FROM `registraties` `tr` WHERE `tr`.`locatie_id` = `tl`.`id` AND `binnen` between :from and :until), 0) AS 'Aantal activeringen',
        IFNULL((SELECT COUNT(distinct `klant_id`) FROM `registraties` `tr` WHERE `tr`.`locatie_id` = `tl`.`id` AND `binnen` between :from and :until AND `tr`.`activering` = 1), 0) AS 'Aantal unieke activeringen',
        IFNULL((SELECT SUM(`kleding`) FROM `registraties` `tr` WHERE `tr`.`locatie_id` = `tl`.`id` AND `binnen` between :from and :until), 0) AS 'Aantal kleding',
        IFNULL((SELECT SUM(`veegploeg`) FROM `registraties` `tr` WHERE `tr`.`locatie_id` = `tl`.`id` AND `binnen` between :from and :until), 0) AS 'Aantal veegploeg',
        IFNULL((SELECT COUNT(distinct `klant_id`) FROM `registraties` `tr` WHERE `tr`.`locatie_id` = `tl`.`id` AND `binnen` between :from and :until AND `tr`.`veegploeg` = 1), 0) AS 'Aantal personen veegploeg',
        naam as order_name
    FROM `locaties` `tl`
    )
UNION
    (SELECT
        'Alle locaties samen',
        IFNULL((SELECT SUM(IF(`douche` = -1, 1, 0)) FROM `registraties` `tr` WHERE `binnen` between :from and :until), 0),
        IFNULL((SELECT SUM(`maaltijd`) FROM `registraties` `tr` WHERE `binnen` between :from and :until), 0),
        IFNULL((SELECT SUM(`activering`) FROM `registraties` `tr` WHERE `binnen` between :from and :until), 0),
        IFNULL((SELECT COUNT(distinct `klant_id`) FROM `registraties` `tr` WHERE `binnen` between :from and :until AND `activering` = 1), 0),
        IFNULL((SELECT SUM(`kleding`) FROM `registraties` `tr` WHERE `binnen` between :from and :until), 0),
        IFNULL((SELECT SUM(`veegploeg`) FROM `registraties` `tr` WHERE `binnen` between :from and :until), 0),
        IFNULL((SELECT COUNT(distinct `klant_id`) FROM `registraties` `tr` WHERE `binnen` between :from and :until AND `veegploeg` = 1), 0),
        'ZZZZZZ' as order_name
    )
) a
ORDER BY order_name
;


-- START 17-18. - Primaire problematiek.sql
-- HEAD: Primaire verslaving van bezoekers
-- FIELDS: v.Primaire problematiek - Primaire problematiek; 0.Aantal mannen - Aantal mannen; 0.Aantal vrouwen - Aantal vrouwen; 0.Totaal - Totaal
-- ARRAY
-- !DISABLE

SELECT
	`naam` AS 'Primaire problematiek',
	(SELECT COUNT(distinct klant_id) FROM `tmp_visitors` `tv` WHERE `tv`.`verslaving_id` <=> `v`.`id` AND `tv`.`geslacht` = 'Man' AND `date` between :from and :until) AS 'Aantal mannen',
	(SELECT COUNT(distinct klant_id) FROM `tmp_visitors` `tv` WHERE `tv`.`verslaving_id` <=> `v`.`id` AND `tv`.`geslacht` = 'Vrouw' AND `date` between :from and :until) AS 'Aantal vrouwen',
	(SELECT COUNT(distinct klant_id) FROM `tmp_visitors` `tv` WHERE `tv`.`verslaving_id` <=> `v`.`id` AND `date` between :from and :until) AS 'Totaal'
FROM (select naam, id from `verslavingen` union select 'Onbekend' naam, null id) v
ORDER BY 4 desc
;

-- START 19. - Gemiddelde verblijfsduur.sql
-- HEAD: Gemiddelde verblijfsduur per locatie
-- Average duration of stay in an inloophuis
-- FIELDS: a.naam - Locatie; a.Gem verblijfsduur mannen - Gem. verblijfsduur mannen; a.Gem verblijfsduur vrouwen - Gem. verblijfsduur vrouwen; a.Gem verblijfsduur totaal - Gem. verblijfsduur totaal
-- ARRAY
-- !DISABLE
-- SUMMARY

select *
  from (
(SELECT
	`naam`,
	IFNULL((SELECT SEC_TO_TIME(AVG(duration)) FROM `tmp_visits` `tv` WHERE `tv`.`locatie_id` = `tl`.`id` AND `tv`.`gender` = 'Man' AND `date` between :from and :until), '-') AS 'Gem verblijfsduur mannen',
	IFNULL((SELECT SEC_TO_TIME(AVG(duration)) FROM `tmp_visits` `tv` WHERE `tv`.`locatie_id` = `tl`.`id` AND `tv`.`gender` = 'Vrouw' AND `date` between :from and :until), '-') AS 'Gem verblijfsduur vrouwen',
	IFNULL((SELECT SEC_TO_TIME(AVG(duration)) FROM `tmp_visits` `tv` WHERE `tv`.`locatie_id` = `tl`.`id` AND `date` between :from and :until), '-') AS 'Gem verblijfsduur totaal',
    naam as order_name
FROM `locaties` `tl`)
UNION
(SELECT
	'Alle locaties samen',
	IFNULL((SELECT SEC_TO_TIME(AVG(duration)) FROM `tmp_visits` `tv` WHERE `tv`.`gender` = 'Man' AND `date` between :from and :until), '-'),
	IFNULL((SELECT SEC_TO_TIME(AVG(duration)) FROM `tmp_visits` `tv` WHERE `tv`.`gender` = 'Vrouw' AND `date` between :from and :until), '-'),
	IFNULL((SELECT SEC_TO_TIME(AVG(duration)) FROM `tmp_visits` `tv` WHERE `date` between :from and :until), '-'),
    'ZZZZZ' as order_name
)) a
order by order_name
;

-- START 20. Gemiddelde verblijfsduur per bezoeker
-- HEAD: Gemiddelde verblijfsduur per bezoeker
-- Average duration of stay in an inloophuis
-- FIELDS: a.label - Duur bezoek; 0.cnt - Aantal bezoekers
-- ARRAY
-- !DISABLE


select label, count(*) cnt from (
SELECT klant_id, AVG(duration) as duration
  FROM `tmp_visits` `tv` 
 WHERE date between :from and :until
 group by klant_id
) a 
join tmp_avgduration a
  on (duration between range_start and range_end)
group by label
order by range_start asc
;

-- START 21-24.- MW  Aantal unieke bezoekers, face-to-face contacten, telefonische consulten, clientgebonden contacten dat contact heeft met MW, per locatie en in totaal
-- HEAD: Bezoekers in contact met MW
-- FIELDS: 0.naam - Locatie; 0.Onbekend - Onbekend; 0.Facetoface contacten - Face-to-face contacten; 0.Telefonische consulten - Telefonische consulten; 0.Clientgebonden contacten - Clientgebonden contacten; 0.Aantal unieke bezoekers - Aantal unieke bezoekers
-- ARRAY
-- !DISABLE
-- SUMMARY

(SELECT
	`naam`,
	IFNULL((SELECT COUNT(*) FROM `verslagen` `v` WHERE `v`.`locatie_id` = `tl`.`id` AND `v`.`contactsoort_id` is null AND v.`datum` between :from and :until), '-') AS 'Onbekend',
	IFNULL((SELECT COUNT(*) FROM `verslagen` `v` WHERE `v`.`locatie_id` = `tl`.`id` AND `v`.`contactsoort_id` = 3 AND v.`datum` between :from and :until), '-') AS 'Facetoface contacten',
	IFNULL((SELECT COUNT(*) FROM `verslagen` `v` WHERE `v`.`locatie_id` = `tl`.`id` AND `v`.`contactsoort_id` = 2 AND v.`datum` between :from and :until), '-') AS 'Telefonische consulten',
	IFNULL((SELECT COUNT(*) FROM `verslagen` `v` WHERE `v`.`locatie_id` = `tl`.`id` AND `v`.`contactsoort_id` = 1 AND v.`datum` between :from and :until), '-') AS 'Clientgebonden contacten',
	IFNULL((SELECT COUNT(distinct klant_id) FROM `verslagen` `v` WHERE `v`.`locatie_id` = `tl`.`id` AND  `v`.`contactsoort_id` is not null AND v.`datum` between :from and :until), '-') AS 'Aantal unieke bezoekers'
FROM `locaties` `tl`
ORDER BY `naam`)
UNION
(SELECT
	'Overige locaties',
	IFNULL((SELECT COUNT(*) FROM `verslagen` `v` WHERE `v`.`contactsoort_id` is null AND v.locatie_id is null AND v.`datum` between :from and :until), '-') AS 'Onbekend',
	IFNULL((SELECT COUNT(*) FROM `verslagen` `v` WHERE `v`.`contactsoort_id` = 3 AND v.locatie_id is null AND v.`datum` between :from and :until), '-') AS 'Face-to-face contacten',
	IFNULL((SELECT COUNT(*) FROM `verslagen` `v` WHERE `v`.`contactsoort_id` = 2 AND v.locatie_id is null AND v.`datum` between :from and :until), '-') AS 'Telefonische consulten',
	IFNULL((SELECT COUNT(*) FROM `verslagen` `v` WHERE `v`.`contactsoort_id` = 1 AND v.locatie_id is null AND v.`datum` between :from and :until), '-') AS 'Clientgebonden contacten',
	IFNULL((SELECT COUNT(distinct klant_id) FROM `verslagen` `v` WHERE v.`datum` between :from and :until AND v.locatie_id is null), '-') AS 'Aantal unieke bezoekers'
)
UNION
(SELECT
	'Alle locaties samen',
	IFNULL((SELECT COUNT(*) FROM `verslagen` `v` WHERE `v`.`contactsoort_id` is null AND v.`datum` between :from and :until), '-') AS 'Onbekend',
	IFNULL((SELECT COUNT(*) FROM `verslagen` `v` WHERE `v`.`contactsoort_id` = 3 AND v.`datum` between :from and :until), '-') AS 'Face-to-face contacten',
	IFNULL((SELECT COUNT(*) FROM `verslagen` `v` WHERE `v`.`contactsoort_id` = 2 AND v.`datum` between :from and :until), '-') AS 'Telefonische consulten',
	IFNULL((SELECT COUNT(*) FROM `verslagen` `v` WHERE `v`.`contactsoort_id` = 1 AND v.`datum` between :from and :until), '-') AS 'Clientgebonden contacten',
	IFNULL((SELECT COUNT(distinct klant_id) FROM `verslagen` `v` WHERE v.`datum` between :from and :until), '-') AS 'Aantal unieke bezoekers'
)
;

-- START 25.- MW Aantal unieke personen waar face to face contact mee is geweest uitsplitsen per nationaliteit per periode waar de rapportage betrekking op heeft.
-- HEAD:  Nationaliteit van bezoekers in contact met MW
-- FIELDS: n.nationaliteit - Nationaliteit; 0.cnt - Aantal unieke personen
-- ARRAY
-- !DISABLE

select n.naam nationaliteit, count(distinct klant_id) cnt
  from verslagen v
  join klanten k
    on (v.klant_id = k.id)
  join nationaliteiten n
    on (k.nationaliteit_id = n.id)
 where contactsoort_id = 3
   and v.datum between :from and :until
 group by 1
 order by n.id
;

-- START 27. - MW - Ingevoerde verslagen per maatschappelijk werker.sql
-- HEAD: Ingevoerde verslagen per MW'er (top 10)
-- FIELDS: 0.Medewerker - Medewerker; 0.Aantal verslagen - Aantal verslagen
-- ARRAY
-- !DISABLE
-- SUMMARY

(SELECT
	CONCAT(`m`.`voornaam`, ' ', IF(`m`.`tussenvoegsel` IS NULL, '', CONCAT(`m`.`tussenvoegsel`, ' ')), `m`.`achternaam`) AS 'Medewerker',
	COUNT(distinct v.id) AS 'Aantal verslagen'
FROM
	`verslagen` `v`
INNER
	JOIN `medewerkers` `m`
	ON `v`.`medewerker_id` = `m`.`id`
WHERE
	v.datum between :from and :until
GROUP BY 1
ORDER BY 2 DESC)
UNION
(SELECT
	"Alle MW'ers samen" AS 'Medewerker',
	COUNT(distinct v.id) AS 'Aantal verslagen'
FROM
	`verslagen` `v`
INNER
	JOIN `medewerkers` `m`
	ON `v`.`medewerker_id` = `m`.`id`
WHERE
	v.datum between :from and :until
GROUP BY 1
ORDER BY 2 DESC
)
;

-- START 26. Aantal doorverwijzingen, per maand en per jaar, per locatie en in totaal
-- HEAD: MW doorverwijzingen per locatie
-- FIELDS: 0.naam - Locatie; 0.cnt - Aantal doorverwijzingen
-- ARRAY
-- !DISABLE
-- SUMMARY
(
select l.naam, count(distinct iv.id) cnt
  from locaties l
  left 
  join verslagen v
    on (v.locatie_id = l.id and v.datum between :from and :until)
  left
  join inventarisaties_verslagen iv
    on (v.id = iv.verslag_id)
 group by 1
)
UNION
(
select 'Alle locaties samen' naam, count(distinct iv.id) cnt
  from locaties l
  left
  join verslagen v
    on (v.locatie_id = l.id and v.datum between :from and :until)
  left
  join inventarisaties_verslagen iv
    on (v.id = iv.verslag_id)
 group by 1
)
;


-- END FILE: keep this at the end of the file
