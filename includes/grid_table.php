<?php

// @todo for grid grade subject
function gridGradeSubject($param = array()) {
    $curl = CURL(WEB_ROOT . "api/grade_subject/grid.php", $param);
    $arr = json_decode($curl,true);
    return $arr;
}

function totalGradeSubject($param = array()) {
    $curl = CURL(WEB_ROOT . "api/grade_subject/total_record.php", $param);
    $arr = json_decode($curl,true);
    return !empty($arr['totalRecord']) ? (int)$arr['totalRecord'] : 1;
}

// @todo for grid grade exam
function gridExam($param = array()) {
    $curl = CURL(WEB_ROOT . "api/exam/grid.php", $param);
    $arr = json_decode($curl,true);
    return $arr;
}

function selectExam($param = array()) {
    $curl = CURL(WEB_ROOT . "api/exam/select.php", $param);
    $arr = json_decode($curl,true);
    return $arr;
}

function totalExam($param = array()) {
    $curl = CURL(WEB_ROOT . "api/exam/total_record.php", $param);
    $arr = json_decode($curl,true);
    return !empty($arr['totalRecord']) ? (int)$arr['totalRecord'] : 1;
}

// @todo for grid grade exam detail
function gridExamDetail($param = array()) {
    $curl = CURL(WEB_ROOT . "api/exam_detail/grid.php", $param);
    $arr = json_decode($curl,true);
    return $arr;
}

function totalExamDetail($param = array()) {
    $curl = CURL(WEB_ROOT . "api/exam_detail/total_record.php", $param);
    $arr = json_decode($curl,true);
    return !empty($arr['totalRecord']) ? (int)$arr['totalRecord'] : 1;
}

// @todo for test
function listTest($param=array()) {
    $curl=CURL(WEB_ROOT."api/test/grid.php",$param);
    $arr = json_decode($curl,true);
    return $arr;
}

function totalTest($param = array()) {
    $curl = CURL(WEB_ROOT . "api/test/total_record.php", $param);
    $arr = json_decode($curl,true);
    return !empty($arr['totalRecord']) ? (int)$arr['totalRecord'] : 1;
}

function selectTest($param = array()) {
    $curl = CURL(WEB_ROOT . "api/test/select.php", $param);
    $arr = json_decode($curl,true);
    return $arr;
}
function gridTable($table='',$param = array()) {
    $curl = CURL(WEB_ROOT . "api/$table/grid.php", $param);
    $arr = json_decode($curl,true);
    return $arr;
}
function InfoTable($table='',$name='',$param = array()) {
    $curl = CURL(WEB_ROOT . "api/$table/$name.php", $param);
    $arr = json_decode($curl,true);
    return $arr;
}
function CountTable($table='',$param = array()) {
    $curl = CURL(WEB_ROOT . "api/$table/count.php", $param);
    $arr = json_decode($curl,true);
    return $arr;
}

// week in year
function WeekInYear($param = array()) {
    $curl = CURL(WEB_ROOT . "api/week/weekinyear.php", $param);
    $arr = json_decode($curl,true);
    return $arr;
}
// year available
function YearAvailable($param = array()) {
    $curl = CURL(WEB_ROOT . "api/week/year_available.php", $param);
    $arr = json_decode($curl,true);
    return $arr;
}

// week
function selectWeek() {
    $arrWeek = [];
    for ($i = 1; $i <= 40; $i++) {
        $item['code'] = $i;
        $item['name'] = 'Tuần ' . $i;
        $arrWeek[] = $item;
    }
    return $arrWeek;
}

// year
function selectYear() {
    $arrYear = [];
    for ($i = date('Y') - 5; $i <= date('Y') + 5; $i++) {
        $item['code'] = $i;
        $item['name'] = 'Năm ' . $i;
        $arrYear[] = $item;
    }
    return $arrYear;
}

// semester
function selectSemester() {
    $arrSemester = [
        0 => [
            'code' => 1,
            'name' => "Kì 1"
        ],
        1 => [
            'code' => 2,
            'name' => "Kì 2"
        ]
    ];
    return $arrSemester;
}
