INSERT INTO team_has_person(team_id,person_id)
SELECT 
  t.id AS team_id,
  p.id AS person_id
 /* pt.id AS prf_team_id,
  at.id AS age_team_id,
  pp.id AS prf_person_id,
  ap.id AS age_person_id,
  JSON_MERGE_PATCH(ptc.`describe`,atc.`describe`) AS team,
  JSON_MERGE_PATCH(pp.`describe`,ap.`describe`) AS person*/
FROM prf_team pt
INNER JOIN prf_team_class ptc ON  pt.prf_team_class_id = ptc.id
INNER JOIN team_class tc ON tc.prf_team_class_id=ptc.id
INNER JOIN age_team_class atc ON tc.age_team_class_id=atc.id
INNER JOIN age_team `at` ON `at`.age_team_class_id = atc.id 
INNER JOIN age_team_has_age_person atap ON atap.age_team_id=at.id
INNER JOIN prf_team_has_prf_person ptpp ON pt.id = ptpp.prf_team_id
INNER JOIN prf_person pp ON ptpp.prf_person_id = pp.id
INNER JOIN age_person ap ON atap.age_person_id = ap.id
INNER JOIN team t ON t.age_team_id = `at`.id AND t.prf_team_id = pt.id
INNER JOIN person p ON p.age_person_id = ap.id AND p.prf_person_id = pp.id


/*WHERE JSON_EXTRACT(ptc.describe,"$.type") = JSON_EXTRACT(atc.describe,"$.type")
  AND JSON_EXTRACT(ptc.describe,"$.status") = JSON_EXTRACT(atc.describe,"$.status")
  AND JSON_EXTRACT(ptc.describe,"$.type") = "Amateur"
  AND JSON_EXTRACT(ptc.describe,"$.status") = "Student-Student"
  AND JSON_EXTRACT(ptc.describe,"$.proficiency") = "Bronze"
  AND JSON_EXTRACT(pp.describe,"$.proficiency") = "Newcomer" 
  AND JSON_EXTRACT(atc.describe,"$.age")="Y10-10"
  AND JSON_EXTRACT(ap.describe,"$.years") = 7*/
