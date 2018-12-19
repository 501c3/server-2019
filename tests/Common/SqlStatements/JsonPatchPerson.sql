SET FOREIGN_KEY_CHECKS=0;
TRUNCATE person;
SET FOREIGN_KEY_CHECKS=1;
INSERT INTO person(age_person_id,prf_person_id,`describe`)
SELECT 	ap.id as age_person_id,
		pp.id as prf_person_id,
        JSON_MERGE_PATCH(pp.describe,ap.describe) as `describe`
  FROM prf_person pp
  INNER JOIN age_person_has_prf_person appp ON pp.id=appp.prf_person_id
  INNER JOIN age_person ap ON appp.age_person_id=ap.id;
  