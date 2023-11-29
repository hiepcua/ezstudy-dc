<?php
// get list school
function listSchool($param=array()) { 
	$curl=CURL(WEB_ROOT."api/school/list.php",$param); 
	$arr_school = json_decode($curl,true);
	return $arr_school;
}
function numberStudentOfschool($param=array()) { 
	$curl=CURL(WEB_ROOT."api/school/countStudent.php",$param); 
	$val = json_decode($curl,true);
	return $val['num'];
}
// get list grade
function listGrade($param=array()) { 
	$curl=CURL(WEB_ROOT."api/grade/list.php",$param); 
	$arr_grade = json_decode($curl,true);
	return $arr_grade;
}
// get list subject
function listSubject($param=array()) { 
	$curl=CURL(WEB_ROOT."api/subject/list.php",$param);
	$arr_subject = json_decode($curl,true);
	return $arr_subject;
}

// get list grade_subject
function listGradeSubject($param=array()) { 
	$curl=CURL(WEB_ROOT."api/grade_subject/list.php",$param); 
	$arr_subject = json_decode($curl,true);
	return $arr_subject;
}
// get list grade_subject
function listGradeOfSubject($param=array()) { 
	$curl=CURL(WEB_ROOT."api/grade_subject/listGradeOfSubject.php",$param); 
	$arr_grade = json_decode($curl,true);
	return $arr_grade;
}
// get list grade_subject
function listSubjectOfGrade($param=array()) { 
	$curl=CURL(WEB_ROOT."api/grade_subject/listSubjectOfGrade.php",$param); 
	$arr_subject = json_decode($curl,true);
	return $arr_subject;
}
// get list student
function listStudent($param=array()) { 
	$curl=CURL(WEB_ROOT."api/student/list.php",$param); 
	$arr_student = json_decode($curl,true);
	return $arr_student;
}

// get grade subject
function listGradeStudent($param=array()) {  
	$curl=CURL(WEB_ROOT."api/student/grade_student.php",$param); 
	$arr = json_decode($curl,true);
	return $arr;
}

// get quiz
function listQuiz($param=array()) { 
	$curl=CURL(WEB_ROOT."api/quiz/list.php",$param); 
	$arr = json_decode($curl,true);
	return $arr;
}