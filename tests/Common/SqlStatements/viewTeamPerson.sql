SELECT 
 at.id as age_team_id,
 pt.id as prf_team_id,
 ap.id as age_person_id,
 pp.id as prf_person_id,
 JSON_MERGE_PATCH(ap.describe,pp.describe),
 JSON_MERGE_PATCH(atc.describe,ptc.describe)
FROM age_team `at`
INNER JOIN age_team_has_age_person atap ON `at`.id = atap.age_team_id
INNER JOIN age_team_has_prf_team atpt ON at.id = atpt.age_team_id
INNER JOIN prf_team pt ON atpt.prf_team_id = pt.id
INNER JOIN age_person ap ON atap.age_person_id = ap.id
INNER JOIN age_person_has_prf_person appp ON ap.id=appp.age_person_id
INNER JOIN prf_person pp ON appp.prf_person_id = pp.id
INNER JOIN age_team_class atc ON `at`.age_team_class_id = atc.id
INNER JOIN prf_team_class ptc ON pt.prf_team_class_id=ptc.id
WHERE JSON_EXTRACT(ap.describe,"$.designate")=JSON_EXTRACT(pp.describe,"$.designate")
AND JSON_EXTRACT(ap.describe,"$.type")=JSON_EXTRACT(pp.describe,"$.type")
AND JSON_EXTRACT(ap.describe,"$.status")=JSON_EXTRACT(pp.describe,"$.status")
AND JSON_EXTRACT(atc.describe,"$.age")="Y05-05"
AND JSON_EXTRACT(ptc.describe,"$.proficiency")="Bronze"
