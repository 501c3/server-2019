SELECT 
`at`.id,
atc.`describe`,
ap1.`describe`,
ap2.`describe`
FROM age_team at
INNER JOIN age_team_class atc ON at.age_team_class_id=atc.id
INNER JOIN age_team_has_age_person atap1 ON `at`.id=atap1.age_team_id
INNER JOIN age_person ap1 ON atap1.age_person_id=ap1.id
INNER JOIN age_team_has_age_person atap2 ON `at`.id=atap2.age_team_id
INNER JOIN age_person ap2 ON atap2.age_person_id=ap2.id
WHERE ap1.id = 4 AND ap1.id<>ap2.id