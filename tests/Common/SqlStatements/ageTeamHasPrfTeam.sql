SET FOREIGN_KEY_CHECKS=0;
TRUNCATE age_team_has_prf_team;
SET FOREIGN_KEY_CHECKS=1;
INSERT IGNORE INTO age_team_has_prf_team(age_team_id,prf_team_id)
SELECT DISTINCT
  at.id as age_team_id,
  pt.id as prf_team_id
FROM age_team `at`
INNER JOIN age_team_class atc ON `at`.age_team_class_id=atc.id
INNER JOIN age_team_class_has_prf_team_class atcptc ON atc.id=atcptc.age_team_class_id
INNER JOIN prf_team_class ptc ON atcptc.prf_team_class_id=ptc.id
INNER JOIN prf_team pt ON ptc.id=pt.prf_team_class_id
/*WHERE ptc.describe->>"$.proficiency"="Bronze" AND ptc.describe->>"$.sex"="Male"*/

