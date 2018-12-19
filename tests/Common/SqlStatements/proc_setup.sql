CREATE PROCEDURE `setup` ()
BEGIN
SET FOREIGN_KEY_CHECKS=0;
TRUNCATE team_class;
TRUNCATE person;
SET FOREIGN_KEY_CHECKS=1;
INSERT INTO person(age_person_id,prf_person_id,`describe`)
SELECT 	ap.id as age_person_id,
		pp.id as prf_person_id,
        JSON_MERGE_PATCH(pp.describe,ap.describe) as `describe`
  FROM prf_person pp
  INNER JOIN age_person_has_prf_person appp ON pp.id=appp.prf_person_id
  INNER JOIN age_person ap ON appp.age_person_id=ap.id;
  
INSERT INTO team_class(age_team_class_id,prf_team_class_id,`describe`)
SELECT 
  atc.id as age_class_id,
  ptc.id as prf_class_id,
  JSON_MERGE_PATCH(atc.describe,ptc.describe) as `describe`
FROM age_team_class atc
INNER JOIN age_team_class_has_prf_team_class atcptc ON atc.id=atcptc.age_team_class_id
INNER JOIN prf_team_class ptc ON atcptc.prf_team_class_id=ptc.id;

END
