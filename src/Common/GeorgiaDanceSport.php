<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/4/18
 * Time: 5:03 PM
 */

namespace App\Common;


class GeorgiaDanceSport
{
    const
        TEACHER_AMATEUR_PROFICIENCIES = ['Pre Championship', 'Championship'],
        TEACHER_PROFESSIONAL_PROFICIENCIES = ['Rising Star', 'Professional'];




    public function buildTeamPersons($team, $year, $partnerProficiencies):array
    {
        $teamCoupleList = [];
        switch ($team['type']) {
            case 'Amateur':
                switch ($team['status']) {
                    case 'Student-Student':
                        $this->buildStudentStudentCouples($teamCoupleList, $team, $year, $partnerProficiencies);
                        break;
                    case 'Teacher-Student':
                        $this->buildTeacherStudentCouples($teamCoupleList,$team,$year);
                }
                break;
            case 'Professional-Amateur':
                switch($team['status']) {
                    case 'Teacher-Student':
                      $this->buildTeacherStudentCouples($teamCoupleList,$team, $year);
                }
        }
        return $teamCoupleList;
    }

    public function buildTeamSolo(array $team, int $year){
        $person = ['type'=>$team['type'],
                   'status'=>$team['status'],
                   'years'=>$year,
                   'proficiency'=>$team['proficiency']];
        return [['team'=>$team, 'persons'=>[$person]]];

    }

    private function buildStudentStudentCouples(array & $teamCoupleList,
                                                array $team,
                                                int $year,
                                                array $partnerProficiencies)
    {
        $ageRange = $this->ageRangeCouple($year);
        $sex = explode('-', $team['sex']);
        $partner1 = ['type' => 'Amateur', 'status' => 'Student', 'proficiency' => $team['proficiency']];
        foreach ($ageRange['older'] as $olderAge) {
            foreach ($ageRange['younger'] as $youngerAge) {
                foreach ($partnerProficiencies as $proficiency) {
                    $partner2 = ['type' => 'Amateur', 'status' => 'Student', 'proficiency' => $proficiency];
                    $partner1['years'] = $olderAge;
                    $partner2['years'] = $youngerAge;
                    if ($sex[0] == $sex[1]) {
                        $partner1['sex'] = $sex[0];
                        $partner2['sex'] = $sex[0];
                        array_push($teamCoupleList,
                            ['team' => $team, 'persons' => [$partner1, $partner2]]);
                    } else {
                        $partner1['sex'] = $sex[0];
                        $partner2['sex'] = $sex[1];
                        array_push($teamCoupleList,
                            ['team' => $team, 'persons' => [$partner1, $partner2]]);
                        $partner1['sex'] = $sex[1];
                        $partner2['sex'] = $sex[0];
                        array_push($teamCoupleList,
                            ['team' => $team, 'persons' => [$partner1, $partner2]]);
                    }

                }
            }
        }
    }


    public function buildTeacherStudentCouples(array &$teamCoupleList,
                                               array $team,
                                               int $year)
    {
        $sex = explode('-', $team['sex']);
        $teacherProficiencies=$team['type']=='Amateur'?self::TEACHER_AMATEUR_PROFICIENCIES:
                                                self::TEACHER_PROFESSIONAL_PROFICIENCIES;
        for ($teacherAge = 16; $teacherAge < 80; $teacherAge++) {
            foreach ($teacherProficiencies as $teacherProficiency) {
                $teacher = ['type'=>'Amateur','status'=>'Teacher',
                            'years'=>$teacherAge, 'proficiency'=>$teacherProficiency];
                $student = ['type'=>'Amateur','status'=>'Student',
                            'years'=>$year, 'proficiency'=>$team['proficiency']];
                if($sex[0]==$sex[1]) {
                    $teacher['sex']=$sex[0];
                    $student['sex']=$sex[0];
                    array_push($teamCoupleList,
                        ['team' => $team,
                         'persons' => [$teacher, $student]]);
                }else{
                    $teacher['sex']=$sex[0];
                    $student['sex']=$sex[1];
                    array_push($teamCoupleList,
                        ['team'=>$team, 'persons'=>[$teacher,$student]]);
                    $teacher['sex']=$sex[1];
                    $student['sex']=$sex[0];
                    array_push($teamCoupleList,
                        ['team'=>$team, 'persons'=>[$teacher,$student]]);
                }
            }
        }
    }


    private function ageRangeCouple($year):array
    {
        if ($year <= 4) {
            return ['younger'=>range(1, $year),
                    'older'=>[$year]];
        } elseif ($year <= 6) {
            return ['younger'=>array_merge([4],range(5, $year)),
                    'older'=>[$year]];
        } elseif ($year <= 9) {
            return ['younger'=>array_merge([5,6],range(7, $year)),
                    'older'=>[$year]];
        } elseif ($year <= 11) {
            return ['younger'=>array_merge([7,8,9],range(10, $year)),
                    'older'=>[$year]];
        } elseif ($year <= 13) {
            return ['younger'=>array_merge([11],range(12, $year)),
                    'older'=>[$year]];
        } elseif ($year <= 15) {
            return ['younger'=>array_merge([13],range(14, $year)),
                    'older'=>[$year]];
        } elseif ($year <= 18) {
            return ['younger' => array_merge([14, 15], range(16, $year)),
                    'older' => [$year]];
        } elseif ($year < 30) {
            return ['younger'=> range(16,$year),
                    'older'=>range($year,99)];
        } elseif ($year < 35) {
            return ['younger'=>range(30,$year),
                    'older'=>range($year,99)];
        } elseif ($year < 40) {
            return ['younger'=>[$year],
                    'older'=>range($year,99)];
        } elseif ($year < 45) {
            return ['younger'=>range(40,$year),
                    'older'=>range($year, 99)];
        } elseif ($year < 50) {
            return ['younger'=>[$year],
                    'older'=>range($year,99)];
        } elseif ($year < 55) {
            return ['younger'=>range(50,$year),
                    'older'=>range($year, 99)];
        } elseif ($year < 60) {
            return ['younger'=>[$year],
                    'older'=>range($year,99)];
        } elseif ($year < 65){
            return ['younger'=>range(60,$year),
                    'older'=>range($year, 99)];
        } elseif ($year < 70) {
            return ['younger'=>[$year],
                    'older'=>range($year,99)];
        } else {
            return ['younger'=>range(70,$year),
                    'older'=>range($year,99)];
        }

    }
}