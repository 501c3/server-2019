SELECT 
  pt.id AS prf_team_id,
  pp1.id AS prf_person_id,
  JSON_MERGE_PATCH(ptc.`describe`,atc.`describe`) AS team,
  JSON_MERGE_PATCH(pp1.`describe`,ap1.`describe`) AS person1,
  JSON_MERGE_PATCH(pp2.`describe`,ap2.`describe`) AS person2
FROM prf_team pt
INNER JOIN prf_team_class ptc ON  pt.prf_team_class_id = ptc.id
INNER JOIN prf_team_has_prf_person ptpp1 ON pt.id = ptpp1.prf_team_id
INNER JOIN prf_team_has_prf_person ptpp2 ON ptpp1.prf_team_id = ptpp2.prf_team_id
INNER JOIN prf_person pp1 ON ptpp1.prf_person_id = pp1.id
INNER JOIN prf_person pp2 ON ptpp2.prf_person_id = pp2.id
INNER JOIN age_team_class_has_prf_team_class atcptc ON ptc.id = atcptc.prf_team_class_id
INNER JOIN age_team_class atc ON atcptc.age_team_class_id=atc.id
INNER JOIN age_team `at` ON atc.id = `at`.age_team_class_id
INNER JOIN age_team_has_age_person atap1 ON atap1.age_team_id=at.id
INNER JOIN age_team_has_age_person atap2 ON atap1.age_team_id=atap2.age_team_id
INNER JOIN age_person ap1 ON atap1.age_person_id = ap1.id
INNER JOIN age_person ap2 ON atap2.age_person_id = ap2.id
WHERE JSON_EXTRACT(ap1.describe,"$.designate") = "A"
  AND JSON_EXTRACT(ap2.describe,"$.designate") = "B"
  AND JSON_EXTRACT(pp1.describe,"$.designate") = "A"
  AND JSON_EXTRACT(pp2.describe,"$.designate") = "B"
  AND JSON_EXTRACT(atc.describe,"$.age") = "Y07-07"
  AND JSON_EXTRACT(ptc.describe,"$.proficiency") = "Bronze"

