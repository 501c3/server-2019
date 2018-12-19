SELECT
  `at`.id as age_id,
   pt.id as prf_id
FROM age_team `at`
INNER JOIN age_team_class atc ON at.age_team_class_id=atc.id
INNER JOIN age_team_class_has_prf_team_class atpt ON atc.id=atpt.age_team_class_id
INNER JOIN prf_team_class ptc ON atpt.prf_team_class_id=ptc.id
INNER JOIN prf_team pt ON pt.prf_team_class_id=ptc.id


