-- START .- Klanten raportage for Ladis
-- HEAD:  LADIS rapportage
-- FIELDS: k.id - ECD ID; k.achternaam - Achternaam; k.geboortedatum - Geboortedatum; g.afkorting - Geslacht; k.BSN - BSN; l.land - Geboorteland; 0.postcode - Postcode; n.naam - Nationaliteit; 0.inkom -  Bron van inkomstem; w.naam - Woonsituatie; pp.naam - Primaire problematiek; 0.versl - Secundaire problematiek; 0.firstTime - Leeftijd eerste gebruik; vp.naam - Duur problematiek; vf.naam - Frequentie gebruik; 0.pgebruik - Wijze van gebruik; 0.vgebruik - Wijze van gebruik secundaire problematiek
-- ARRAY
-- !DISABLE
select k.id, k.achternaam, k.geboortedatum, k.BSN, g.afkorting, l.land, n.naam, substr(i.postcode, 1, 4) postcode, GROUP_CONCAT(distinct v.naam SEPARATOR ' ') versl, GROUP_CONCAT(distinct ink.naam SEPARATOR ' ') inkom, w.naam, pp.naam, vp.naam, vf.naam, GROUP_CONCAT(distinct pg.naam SEPARATOR ' ') pgebruik, GROUP_CONCAT(distinct vg.naam SEPARATOR ' ') vgebruik, FLOOR(DATEDIFF(i.eerste_gebruik, k.geboortedatum) /365) firstTime
  FROM klanten k
  LEFT JOIN geslachten g
    ON (k.geslacht_id = g.id)
  LEFT JOIN landen l
    ON (k.land_id = l.id)
  LEFT JOIN nationaliteiten n
    ON (k.nationaliteit_id = n.id)
  LEFT JOIN intakes i
    ON (k.laste_intake_id = i.id)

  LEFT JOIN woonsituaties w
    ON (i.woonsituatie_id = w.id)

  LEFT JOIN verslavingen pp
    ON (i.primaireproblematiek_id = pp.id)

  LEFT JOIN verslavingsperiodes vp
    ON (i.verslavingsperiode_id = vp.id)

  LEFT JOIN verslavingsfrequenties vf
    ON (i.verslavingsfrequentie_id = vf.id)


  LEFT JOIN intakes_verslavingen iv
    ON (iv.intake_id = i.id)
  LEFT JOIN verslavingen v
    ON (v.id = iv.verslaving_id)

  LEFT JOIN inkomens_intakes iink
    ON (iink.intake_id = i.id)
  LEFT JOIN inkomens ink
    ON (ink.id = iink.inkomen_id)

  LEFT JOIN  intakes_primaireproblematieksgebruikswijzen int_pri
    ON (int_pri.intake_id = i.id)
  LEFT JOIN verslavingsgebruikswijzen pg
    ON (pg.id = int_pri.primaireproblematieksgebruikswijze_id)

  LEFT JOIN intakes_verslavingsgebruikswijzen int_ver
    ON (int_ver.intake_id = i.id)
  LEFT JOIN verslavingsgebruikswijzen vg
    ON (vg.id = int_ver.verslavingsgebruikswijze_id)

 GROUP BY k.id;

-- LINE BREAK IS NEEDED TO KEEP THE LAST LINE

