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
WHERE tl.datum_van <= :until AND (tl.datum_tot >= :from OR tl.datum_tot IS NULL)
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

-- END FILE: keep this at the end of the file
