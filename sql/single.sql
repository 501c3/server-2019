SELECT 
  pt.id AS prf_team_id,
  pp.id AS prf_person_id,
  JSON_MERGE_PATCH(ptc.`describe`,atc.`describe`) AS team,
  JSON_MERGE_PATCH(pp.`describe`,ap.`describe`) AS person1
FROM prf_team pt
INNER JOIN prf_team_class ptc ON  pt.prf_team_class_id = ptc.id
INNER JOIN prf_team_has_prf_person ptpp ON pt.id = ptpp.prf_team_id
INNER JOIN prf_person pp ON ptpp.prf_person_id = pp.id
INNER JOIN age_team_class_has_prf_team_class atcptc ON ptc.id = atcptc.prf_team_class_id
INNER JOIN age_team_class atc ON atcptc.age_team_class_id=atc.id
INNER JOIN age_team `at` ON atc.id = `at`.age_team_class_id
INNER JOIN age_team_has_age_person atap ON atap.age_team_id=at.id
INNER JOIN age_person ap ON atap.age_person_id = ap.id
WHERE JSON_EXTRACT(ap.describe,"$.designate") = "A"
  AND JSON_EXTRACT(pp.describe,"$.designate") = "A"
  AND JSON_EXTRACT(ptc.describe,"$.status") = "Student"
  AND JSON_EXTRACT(atc.describe,"$.age") = "Y07-07"
  AND JSON_EXTRACT(ptc.describe,"$.proficiency") = "Bronze"

