-- START 1-4.Number of unique visitors in a period of time split on gender and location
-- HEAD: Bezoekers per dag openstelling
-- FIELDS: a.naam - Locatie; a.Dagen geopend - Dagen geopend; a.Mannen uniek - Mannen uniek; a.Vrouwen uniek - Vrouwen uniek; a.Totaal uniek - Totaal uniek; a.Mannen per dag - Mannen per dag; a.Vrouwen per dag - Vrouwen per dag; a.Totaal per dag - Totaal per dag
-- ARRAY
-- !DISABLE
-- SUMMARY
SELECT *
  FROM (
(SELECT
    l.`naam`,
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
    l.naam as order_naam
FROM `locaties` `l` USE INDEX (naam)
LEFT JOIN
    (SELECT count(open_day) AS open_days, locatie_id
            FROM tmp_open_days USE INDEX (locatie_id)
           WHERE `open_day` between :from and :until
           GROUP BY tmp_open_days.locatie_id) o
    ON `l`.`id` = `o`.`locatie_id`
INNER JOIN `inloop_locatie_locatietype` AS ill ON l.id = ill.locatie_id
INNER JOIN locatie_type lt ON ill.locatietype_id = lt.id AND lt.naam IN (:locatietypes)
WHERE l.datum_van <= :until AND (l.datum_tot >= :from OR l.datum_tot IS NULL)
ORDER BY l.`naam`)
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
     (SELECT count(open_day) AS open_days, locatie_id
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
-- !DISABLE --werkt niet.
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
     )
)
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
        tl.`naam`,
        IFNULL((SELECT SUM(IF(`douche` = -1, 1, 0)) FROM `tmp_inloopdiensten` `tr` WHERE `tr`.`locatie_id` = `tl`.`id` AND `date` >= :from AND `date` <= :until), 0) AS 'Aantal douches',
        IFNULL((SELECT SUM(`maaltijd`) FROM `tmp_inloopdiensten` `tr` WHERE `tr`.`locatie_id` = `tl`.`id` AND `date` >= :from AND `date` <= :until), 0) AS 'Aantal maaltijden',
        IFNULL((SELECT SUM(`activering`) FROM `tmp_inloopdiensten` `tr` WHERE `tr`.`locatie_id` = `tl`.`id` AND `date` >= :from AND `date` <= :until), 0) AS 'Aantal activeringen',
        IFNULL((SELECT COUNT(distinct `klant_id`) FROM `tmp_inloopdiensten` `tr` WHERE `tr`.`locatie_id` = `tl`.`id` AND `date` >= :from AND `date` <= :until AND `tr`.`activering` = 1), 0) AS 'Aantal unieke activeringen',
        IFNULL((SELECT SUM(`kleding`) FROM `tmp_inloopdiensten` `tr` WHERE `tr`.`locatie_id` = `tl`.`id` AND `date` >= :from AND `date` <= :until), 0) AS 'Aantal kleding',
        IFNULL((SELECT SUM(`veegploeg`) FROM `tmp_inloopdiensten` `tr` WHERE `tr`.`locatie_id` = `tl`.`id` AND `date` >= :from AND `date` <= :until), 0) AS 'Aantal veegploeg',
        IFNULL((SELECT COUNT(distinct `klant_id`) FROM `tmp_inloopdiensten` `tr` WHERE `tr`.`locatie_id` = `tl`.`id` AND `date` >= :from AND `date` <= :until AND `tr`.`veegploeg` = 1), 0) AS 'Aantal personen veegploeg',
        tl.naam as order_name
    FROM `locaties` `tl`
     INNER JOIN `inloop_locatie_locatietype` AS ill ON tl.id = ill.locatie_id
     INNER JOIN locatie_type lt ON ill.locatietype_id = lt.id AND lt.naam IN (:locatietypes)
    WHERE tl.datum_van <= :until AND (tl.datum_tot >= :from OR tl.datum_tot IS NULL)
    )
UNION
    (SELECT
        'Alle locaties samen',
        IFNULL((SELECT SUM(IF(`douche` = -1, 1, 0)) FROM `tmp_inloopdiensten` `tr` WHERE `date` >= :from AND `date` <= :until), 0),
        IFNULL((SELECT SUM(`maaltijd`) FROM `tmp_inloopdiensten` `tr` WHERE `date` >= :from AND `date` <= :until), 0),
        IFNULL((SELECT SUM(`activering`) FROM `tmp_inloopdiensten` `tr` WHERE `date` >= :from AND `date` <= :until), 0),
        IFNULL((SELECT COUNT(distinct `klant_id`) FROM `tmp_inloopdiensten` `tr` WHERE `date` >= :from AND `date` <= :until AND `activering` = 1), 0),
        IFNULL((SELECT SUM(`kleding`) FROM `tmp_inloopdiensten` `tr` WHERE `date` >= :from AND `date` <= :until), 0),
        IFNULL((SELECT SUM(`veegploeg`) FROM `tmp_inloopdiensten` `tr` WHERE `date` >= :from AND `date` <= :until), 0),
        IFNULL((SELECT COUNT(distinct `klant_id`) FROM `tmp_inloopdiensten` `tr` WHERE `date` >= :from AND `date` <= :until AND `veegploeg` = 1), 0),
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
-- ORDER BY 4 desc -- Disabled by JTB to prevent filesort.
;

-- START 19. - Gemiddelde verblijfsduur.sql
-- HEAD: Gemiddelde verblijfsduur per locatie
-- Average duration of stay in an inloophuis
-- FIELDS: a.naam - Locatie; a.Gem verblijfsduur mannen - Gem. verblijfsduur mannen; a.Gem verblijfsduur vrouwen - Gem. verblijfsduur vrouwen; a.Gem verblijfsduur totaal - Gem. verblijfsduur totaal
-- ARRAY
-- !DISABLE
-- SUMMARY
SELECT *
  FROM (
(SELECT
    tl.`naam`,
    IFNULL((SELECT SEC_TO_TIME(AVG(duration)) FROM `tmp_visits` `tv` USE INDEX (locatie_gender_date) WHERE `tv`.`locatie_id` = `tl`.`id` AND `tv`.`gender` = 'Man' AND `date` between :from and :until), '-') AS 'Gem verblijfsduur mannen',
    IFNULL((SELECT SEC_TO_TIME(AVG(duration)) FROM `tmp_visits` `tv` USE INDEX (locatie_gender_date) WHERE `tv`.`locatie_id` = `tl`.`id` AND `tv`.`gender` = 'Vrouw' AND `date` between :from and :until), '-') AS 'Gem verblijfsduur vrouwen',
    IFNULL((SELECT SEC_TO_TIME(AVG(duration)) FROM `tmp_visits` `tv` USE INDEX (locatie_gender_date) WHERE `tv`.`locatie_id` = `tl`.`id` AND `date` between :from and :until), '-') AS 'Gem verblijfsduur totaal',
    tl.naam as order_name
FROM `locaties` `tl`
 INNER JOIN `inloop_locatie_locatietype` AS ill ON tl.id = ill.locatie_id
 INNER JOIN locatie_type lt ON ill.locatietype_id = lt.id AND lt.naam IN (:locatietypes)
WHERE tl.datum_van <= :until AND (tl.datum_tot >= :from OR tl.datum_tot IS NULL)
)
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

-- END FILE: keep this at the end of the file
