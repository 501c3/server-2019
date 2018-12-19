CREATE DEFINER=`root`@`localhost` PROCEDURE `ageTeamHasPrfTeam`()
BEGIN
  declare age_team_id int2;
  declare prf_team_id int2;
  declare no_more_records int1;
  declare csr cursor for
  select
		`at`.id as age_id,
		pt.id as prf_id
	from  age_team `at`
	inner join age_team_class atc ON `at`.age_team_class_id=atc.id
	inner join age_team_class_has_prf_team_class atcptc ON atc.id=atcptc.age_team_class_id
	inner join prf_team_class ptc ON atcptc.prf_team_class_id=ptc.id
	inner join prf_team pt ON ptc.id=pt.prf_team_class_id;
  declare continue handler for not found set no_more_records = 1; 
  set no_more_records = 0;
  open csr;
  build_loop: repeat
	fetch csr into age_team_id,prf_team_id;
    insert into age_team_has_prf_team(age_team_id,prf_team_id)
       value (age_team_id,prf_team_id);
  until no_more_records
  end repeat;
  close csr;
  set no_more_records = 0;
END