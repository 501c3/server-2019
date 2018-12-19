SET FOREIGN_KEY_CHECKS=0;
TRUNCATE team_class;
SET FOREIGN_KEY_CHECKS=1;
INSERT INTO team_class(age_team_class_id,prf_team_class_id,`describe`)
SELECT 
  atc.id as age_class_id,
  ptc.id as prf_class_id,
  JSON_MERGE_PATCH(atc.describe,ptc.describe) as `describe`
FROM age_team_class atc
INNER JOIN age_team_class_has_prf_team_class atcptc ON atc.id=atcptc.age_team_class_id
INNER JOIN prf_team_class ptc ON atcptc.prf_team_class_id=ptc.id;
