-- START 21-24.- MW  Aantal unieke bezoekers, face-to-face contacten, telefonische consulten, clientgebonden contacten dat contact heeft met MW, per locatie en in totaal
-- HEAD: Bezoekers in contact met MW
-- FIELDS: 0.naam - Locatie; 0.Aantal contactmomenten - Aantal contactmomenten; 0.Aantal unieke bezoekers - Aantal unieke bezoekers
-- ARRAY
-- !DISABLE
-- SUMMARY
(SELECT
    `tl`.`naam` AS naam,
    IFNULL((SELECT SUM(v.aantalContactmomenten) FROM `verslagen` `v` WHERE `v`.`locatie_id` = `tl`.`id` AND v.`datum` between :from and :until), '-') AS 'Aantal contactmomenten',
    IFNULL((SELECT COUNT(distinct klant_id) FROM `verslagen` `v` WHERE `v`.`locatie_id` = `tl`.`id`  AND v.`datum` between :from and :until), '-') AS 'Aantal unieke bezoekers'
FROM `locaties` `tl`
INNER JOIN inloop_locatie_locatietype ill on tl.id = ill.locatie_id
INNER JOIN locatie_type lt on ill.locatietype_id = lt.id AND lt.naam IN (:locatietypes)
WHERE tl.datum_van <= :until AND (tl.datum_tot >= :from OR tl.datum_tot IS NULL)
ORDER BY `tl`.`naam` ASC)
# UNION
# (SELECT
#     'Overige locaties',
#     IFNULL((SELECT SUM(v.aantalContactmomenten)
#             FROM `verslagen` `v`
#                 INNER JOIN inloop_locatie_locatietype ill on v.locatie_id = ill.locatie_id
#                 INNER JOIN locatie_type lt on ill.locatietype_id = lt.id AND lt.naam IN (:locatietypes)
#             WHERE `v`.`contactsoort_id` is null AND v.locatie_id is null AND v.`datum` between :from and :until)
#         , '-')
#         AS 'Aantal contactmomenten',
#
#     IFNULL((SELECT COUNT(distinct klant_id)
#             FROM `verslagen` `v`
#                  INNER JOIN inloop_locatie_locatietype ill on v.locatie_id = ill.locatie_id
#                  INNER JOIN locatie_type lt on ill.locatietype_id = lt.id AND lt.naam IN (:locatietypes)
#             WHERE v.`datum` between :from and :until AND v.locatie_id is null)
#         , '-')
#         AS 'Aantal unieke bezoekers'
# )
UNION
(SELECT
    'Alle locaties samen' AS 'naam',
    IFNULL((SELECT SUM(v.aantalContactmomenten)
            FROM `verslagen` `v`
                 INNER JOIN inloop_locatie_locatietype ill on v.locatie_id = ill.locatie_id
                 INNER JOIN locatie_type lt on ill.locatietype_id = lt.id AND lt.naam IN (:locatietypes)
            WHERE v.`datum` between :from and :until), '-') AS 'Aantal contactmomenten',

    IFNULL((SELECT COUNT(distinct klant_id)
            FROM `verslagen` `v`
                 INNER JOIN inloop_locatie_locatietype ill on v.locatie_id = ill.locatie_id
                 INNER JOIN locatie_type lt on ill.locatietype_id = lt.id AND lt.naam IN (:locatietypes)
            WHERE v.`datum` between :from and :until), '-') AS 'Aantal unieke bezoekers'
)
ORDER BY naam ASC
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
 order by cnt DESC
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
ORDER BY 2 DESC
;

-- END FILE: keep this at the end of the file
