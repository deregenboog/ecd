-- START 1 Activering in Locatie = 1
-- Number of douches/kleding/maaltijd/activeringen per location per period
-- HEAD: Activering in Blaka Watra
-- FIELDS: a.klant_id - ID; a.naam - Naam
-- ARRAY
-- !DISABLE
select *
from 
(SELECT distinct `klant_id`, CONCAT(IF(`k`.`voornaam` IS NULL, '', `k`.`voornaam`), ' ', IF(`k`.`tussenvoegsel` IS NULL, '', CONCAT(`k`.`tussenvoegsel`, ' ')), IF(`k`.`achternaam` IS NULL, '', `k`.`achternaam`), IF(`k`.`roepnaam` IS NULL, '', CONCAT(' (', `k`.`roepnaam`, ')'))) `naam`  FROM `registraties` `r` JOIN `klanten` `k` ON `klant_id`=`k`.id
WHERE `r`.`locatie_id` = 1
AND `binnen` between :from and :until AND `r`.`activering` = 1 
-- order by order_name
) a
;

-- START 2 Activering in Locatie = 2
-- Number of douches/kleding/maaltijd/activeringen per location per period
-- HEAD: Activering in Princehof
-- FIELDS: a.klant_id - ID; a.naam - Naam
-- ARRAY
-- !DISABLE
select *
from 
(SELECT distinct `klant_id`, CONCAT(IF(`k`.`voornaam` IS NULL, '', `k`.`voornaam`), ' ', IF(`k`.`tussenvoegsel` IS NULL, '', CONCAT(`k`.`tussenvoegsel`, ' ')), IF(`k`.`achternaam` IS NULL, '', `k`.`achternaam`), IF(`k`.`roepnaam` IS NULL, '', CONCAT(' (', `k`.`roepnaam`, ')'))) `naam`  FROM `registraties` `r` JOIN `klanten` `k` ON `klant_id`=`k`.id
WHERE `r`.`locatie_id` = 2
AND `binnen` between :from and :until AND `r`.`activering` = 1 
-- order by order_name
) a
;
-- START 3 Activering in Locatie = 5
-- Number of douches/kleding/maaltijd/activeringen per location per period
-- HEAD: Activering in AMOC
-- FIELDS: a.klant_id - ID; a.naam - Naam
-- ARRAY
-- !DISABLE
select *
from 
(SELECT distinct `klant_id`, CONCAT(IF(`k`.`voornaam` IS NULL, '', `k`.`voornaam`), ' ', IF(`k`.`tussenvoegsel` IS NULL, '', CONCAT(`k`.`tussenvoegsel`, ' ')), IF(`k`.`achternaam` IS NULL, '', `k`.`achternaam`), IF(`k`.`roepnaam` IS NULL, '', CONCAT(' (', `k`.`roepnaam`, ')'))) `naam`  FROM `registraties` `r` JOIN `klanten` `k` ON `klant_id`=`k`.id
WHERE `r`.`locatie_id` = 5
AND `binnen` between :from and :until AND `r`.`activering` = 1 
-- order by order_name
) a
;

-- START 4 Activering in Locatie = 9
-- Number of douches/kleding/maaltijd/activeringen per location per period
-- HEAD: Activering in De Eik
-- FIELDS: a.klant_id - ID; a.naam - Naam
-- ARRAY
-- !DISABLE
select *
from 
(SELECT distinct `klant_id`, CONCAT(IF(`k`.`voornaam` IS NULL, '', `k`.`voornaam`), ' ', IF(`k`.`tussenvoegsel` IS NULL, '', CONCAT(`k`.`tussenvoegsel`, ' ')), IF(`k`.`achternaam` IS NULL, '', `k`.`achternaam`), IF(`k`.`roepnaam` IS NULL, '', CONCAT(' (', `k`.`roepnaam`, ')'))) `naam`  FROM `registraties` `r` JOIN `klanten` `k` ON `klant_id`=`k`.id
WHERE `r`.`locatie_id` = 9
AND `binnen` between :from and :until AND `r`.`activering` = 1 
-- order by order_name
) a
;
-- START 5 Activering in Locatie = 10
-- Number of douches/kleding/maaltijd/activeringen per location per period
-- HEAD: Activering in De Kloof
-- FIELDS: a.klant_id - ID; a.naam - Naam
-- ARRAY
-- !DISABLE
select *
from 
(SELECT distinct `klant_id`, CONCAT(IF(`k`.`voornaam` IS NULL, '', `k`.`voornaam`), ' ', IF(`k`.`tussenvoegsel` IS NULL, '', CONCAT(`k`.`tussenvoegsel`, ' ')), IF(`k`.`achternaam` IS NULL, '', `k`.`achternaam`), IF(`k`.`roepnaam` IS NULL, '', CONCAT(' (', `k`.`roepnaam`, ')'))) `naam`  FROM `registraties` `r` JOIN `klanten` `k` ON `klant_id`=`k`.id
WHERE `r`.`locatie_id` = 10
AND `binnen` between :from and :until AND `r`.`activering` = 1 
-- order by order_name
) a
;
-- START 6 Activering in Locatie = 11
-- Number of douches/kleding/maaltijd/activeringen per location per period
-- HEAD: Activering in Makom
-- FIELDS: a.klant_id - ID; a.naam - Naam
-- ARRAY
-- !DISABLE
select *
from 
(SELECT distinct `klant_id`, CONCAT(IF(`k`.`voornaam` IS NULL, '', `k`.`voornaam`), ' ', IF(`k`.`tussenvoegsel` IS NULL, '', CONCAT(`k`.`tussenvoegsel`, ' ')), IF(`k`.`achternaam` IS NULL, '', `k`.`achternaam`), IF(`k`.`roepnaam` IS NULL, '', CONCAT(' (', `k`.`roepnaam`, ')'))) `naam`  FROM `registraties` `r` JOIN `klanten` `k` ON `klant_id`=`k`.id
WHERE `r`.`locatie_id` = 11
AND `binnen` between :from and :until AND `r`.`activering` = 1 
-- order by order_name
) a
;
-- START 7 Activering in Locatie = 12
-- Number of douches/kleding/maaltijd/activeringen per location per period
-- HEAD: Activering in Nachtopvang De Regenboog Groep
-- FIELDS: a.klant_id - ID; a.naam - Naam
-- ARRAY
-- !DISABLE
select *
from 
(SELECT distinct `klant_id`, CONCAT(IF(`k`.`voornaam` IS NULL, '', `k`.`voornaam`), ' ', IF(`k`.`tussenvoegsel` IS NULL, '', CONCAT(`k`.`tussenvoegsel`, ' ')), IF(`k`.`achternaam` IS NULL, '', `k`.`achternaam`), IF(`k`.`roepnaam` IS NULL, '', CONCAT(' (', `k`.`roepnaam`, ')'))) `naam`  FROM `registraties` `r` JOIN `klanten` `k` ON `klant_id`=`k`.id
WHERE `r`.`locatie_id` = 12
AND `binnen` between :from and :until AND `r`.`activering` = 1 
-- order by order_name
) a
;
-- START 8 Activering in Locatie = 13
-- Number of douches/kleding/maaltijd/activeringen per location per period
-- HEAD: Activering in Ondro Bong
-- FIELDS: a.klant_id - ID; a.naam - Naam
-- ARRAY
-- !DISABLE
select *
from 
(SELECT distinct `klant_id`, CONCAT(IF(`k`.`voornaam` IS NULL, '', `k`.`voornaam`), ' ', IF(`k`.`tussenvoegsel` IS NULL, '', CONCAT(`k`.`tussenvoegsel`, ' ')), IF(`k`.`achternaam` IS NULL, '', `k`.`achternaam`), IF(`k`.`roepnaam` IS NULL, '', CONCAT(' (', `k`.`roepnaam`, ')'))) `naam`  FROM `registraties` `r` JOIN `klanten` `k` ON `klant_id`=`k`.id
WHERE `r`.`locatie_id` = 13
AND `binnen` between :from and :until AND `r`.`activering` = 1 
-- order by order_name
) a
;
-- START 9 Activering in Locatie = 14
-- Number of douches/kleding/maaltijd/activeringen per location per period
-- HEAD: Activering in Oud West
-- FIELDS: a.klant_id - ID; a.naam - Naam
-- ARRAY
-- !DISABLE
select *
from 
(SELECT distinct `klant_id`, CONCAT(IF(`k`.`voornaam` IS NULL, '', `k`.`voornaam`), ' ', IF(`k`.`tussenvoegsel` IS NULL, '', CONCAT(`k`.`tussenvoegsel`, ' ')), IF(`k`.`achternaam` IS NULL, '', `k`.`achternaam`), IF(`k`.`roepnaam` IS NULL, '', CONCAT(' (', `k`.`roepnaam`, ')'))) `naam`  FROM `registraties` `r` JOIN `klanten` `k` ON `klant_id`=`k`.id
WHERE `r`.`locatie_id` = 14
AND `binnen` between :from and :until AND `r`.`activering` = 1 
-- order by order_name
) a
;
-- START 10 Activering in Locatie = 15
-- Number of douches/kleding/maaltijd/activeringen per location per period
-- HEAD: Activering in De Spreekbuis
-- FIELDS: a.klant_id - ID; a.naam - Naam
-- ARRAY
-- !DISABLE
select *
from 
(SELECT distinct `klant_id`, CONCAT(IF(`k`.`voornaam` IS NULL, '', `k`.`voornaam`), ' ', IF(`k`.`tussenvoegsel` IS NULL, '', CONCAT(`k`.`tussenvoegsel`, ' ')), IF(`k`.`achternaam` IS NULL, '', `k`.`achternaam`), IF(`k`.`roepnaam` IS NULL, '', CONCAT(' (', `k`.`roepnaam`, ')'))) `naam`  FROM `registraties` `r` JOIN `klanten` `k` ON `klant_id`=`k`.id
WHERE `r`.`locatie_id` = 15
AND `binnen` between :from and :until AND `r`.`activering` = 1 
-- order by order_name
) a
;
-- START 11 Activering in Locatie = 16
-- Number of douches/kleding/maaltijd/activeringen per location per period
-- HEAD: Activering in Tape Rienks Huis
-- FIELDS: a.klant_id - ID; a.naam - Naam
-- ARRAY
-- !DISABLE
select *
from 
(SELECT distinct `klant_id`, CONCAT(IF(`k`.`voornaam` IS NULL, '', `k`.`voornaam`), ' ', IF(`k`.`tussenvoegsel` IS NULL, '', CONCAT(`k`.`tussenvoegsel`, ' ')), IF(`k`.`achternaam` IS NULL, '', `k`.`achternaam`), IF(`k`.`roepnaam` IS NULL, '', CONCAT(' (', `k`.`roepnaam`, ')'))) `naam`  FROM `registraties` `r` JOIN `klanten` `k` ON `klant_id`=`k`.id
WHERE `r`.`locatie_id` = 16
AND `binnen` between :from and :until AND `r`.`activering` = 1 
-- order by order_name
) a
;
-- START 12 Activering in Locatie = 17
-- Number of douches/kleding/maaltijd/activeringen per location per period
-- HEAD: Activering in Vrouwen Nacht Opvang
-- FIELDS: a.klant_id - ID; a.naam - Naam
-- ARRAY
-- !DISABLE
select *
from 
(SELECT distinct `klant_id`, CONCAT(IF(`k`.`voornaam` IS NULL, '', `k`.`voornaam`), ' ', IF(`k`.`tussenvoegsel` IS NULL, '', CONCAT(`k`.`tussenvoegsel`, ' ')), IF(`k`.`achternaam` IS NULL, '', `k`.`achternaam`), IF(`k`.`roepnaam` IS NULL, '', CONCAT(' (', `k`.`roepnaam`, ')'))) `naam`  FROM `registraties` `r` JOIN `klanten` `k` ON `klant_id`=`k`.id
WHERE `r`.`locatie_id` = 17
AND `binnen` between :from and :until AND `r`.`activering` = 1 
-- order by order_name
) a
;
-- START 13 Activering in Locatie = 18
-- Number of douches/kleding/maaltijd/activeringen per location per period
-- HEAD: Activering in Westerpark
-- FIELDS: a.klant_id - ID; a.naam - Naam
-- ARRAY
-- !DISABLE
select *
from 
(SELECT distinct `klant_id`, CONCAT(IF(`k`.`voornaam` IS NULL, '', `k`.`voornaam`), ' ', IF(`k`.`tussenvoegsel` IS NULL, '', CONCAT(`k`.`tussenvoegsel`, ' ')), IF(`k`.`achternaam` IS NULL, '', `k`.`achternaam`), IF(`k`.`roepnaam` IS NULL, '', CONCAT(' (', `k`.`roepnaam`, ')'))) `naam`  FROM `registraties` `r` JOIN `klanten` `k` ON `klant_id`=`k`.id
WHERE `r`.`locatie_id` = 18
AND `binnen` between :from and :until AND `r`.`activering` = 1 
-- order by order_name
) a
;
