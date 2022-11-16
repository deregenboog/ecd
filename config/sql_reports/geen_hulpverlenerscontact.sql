-- START
-- HEAD: Geen hulpverlenerscontact
-- FIELDS: 0.naam - Naam; 0.last_intake - Laatste intake; 0.last_hi5_intake - Laatste hi5 intake; 0.last_hi5_evaluatie - Laatste hi5 evaluatie; 0.last_verslag - Laatste verslag; 0.last_inloophuis - Laatste inloophuis
-- ARRAY
-- !DISABLE
-- SUMMARY
select k.id, concat_ws(' ', k.voornaam, k.tussenvoegsel, k.achternaam) naam, k.roepnaam,
       max(i1.datum_intake) last_intake, max(hi.datum_intake) last_hi5_intake,
       max(he.datum_intake) last_hi5_evaluatie,
       max(ve.datum) last_verslag, max(inloop.datum_intake) last_inloophuis
  from klanten k
  join (
       select max(ii.datum_intake) last_intake, ii.klant_id
         from intakes ii
        where ii.datum_intake < :until
          and (ii.locatie1_id = :location or ii.locatie2_id = :location)
        group by ii.klant_id) im
    on k.id = im.klant_id
  join intakes i1
    on (k.id = i1.klant_id and im.last_intake = i1.datum_intake)
  left
  join (hi5_intakes hi)
    on (k.id = hi.klant_id and hi.datum_intake < :until)
  left
  join (hi5_evaluaties he)
    on (k.id = he.klant_id and he.datum_intake < :until)
  left
  join (verslagen ve)
    on (k.id = ve.klant_id and ve.datum < :until)
  left
  join intakes inloop
    on (k.id = inloop.klant_id and inloop.datum_intake < :until and inloop.inloophuis)
 where i1.datum_intake < :until
   and (i1.locatie1_id = :location or i1.locatie2_id = :location)
   and k.geslacht_id in (:gender)
 group by 1, 2, 3
 having count(i1.id) > 0
    and last_intake < :from
    and (last_hi5_intake is null or last_hi5_intake < :from)
    and (last_hi5_evaluatie is null or last_hi5_evaluatie < :from)
    and (last_verslag is null or last_verslag < :from)
  order by 2
;
