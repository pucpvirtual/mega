<?php

include('../../config.php');
include('model.php');

$id = required_param('id',PARAM_INT);
$xls = optional_param('xls',0,PARAM_INT);

$model = new Mega_Model;

$PAGE->set_url('/report/mega/view.php');

require_login();

$context = get_context_instance(CONTEXT_SYSTEM);

$report = $model->get_report($id);

$name = $report->name;

switch($report->type){
	case '1':
		include('lib/grade.php');
		$view = new Mega_grade($model);
		break;
	case '2':
		include('lib/quiz.php');
		$view = new Mega_quiz($model);
		break;
	case '3':
		include('lib/scorm.php');
		$view = new Mega_scorm($model);
		break;
}

$sql = "SELECT c.id,r.roleid 
		FROM {role_assignments} r 
		INNER JOIN {context} ctx ON r.contextid = ctx.id
		INNER JOIN {course} c oN ctx.instanceid = c.id
		WHERE r.userid = ? AND r.roleid <> ?";

$params = array($USER->id,5);

$roles = $DB->get_records_sql_menu($sql,$params);

$goout = false;

if($report->element != '0'){

	if($report->type == 1){

		if(!isset($roles[$report->element])) $goout = true;

	}else{

		$table = ($report->type == 2)? 'quiz' : 'scorm';
		$course = $DB->get_field($table,'course',array('id'=>$report->element));

		if(!isset($roles[$course])) $goout = true;
	}

}else{

		$courses = $DB->get_records('course',array('category'=>$report->category),'id','id');

		foreach($courses as $c){
			if(!isset($roles[$c->id])){
				$goout = true;
				break;
			}
		}
}

if(is_siteadmin()) $goout = false;

if(!$goout)	$view->generate($report->element,$report->category);	


if(empty($xls)){
	$PAGE->set_context($context);

	$PAGE->navbar->add($name);

	$PAGE->set_title($name);

	$PAGE->set_heading($name);

	$link = new moodle_url('/report/mega/index.php');	

	echo $OUTPUT->header();

}else{
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Content-type:   application/x-msexcel; charset=utf-8");
	header("Content-Disposition: attachment; filename=Reporte.xls"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); 	
	echo html_writer::start_tag('html',array('lang'=>'es-pe'));
	echo html_writer::empty_tag('meta',array('charset'=>'utf-8'));
}


	if(!$goout){
		if(empty($xls)){
			echo html_writer::start_tag('div');
				echo html_writer::tag('a',get_string('download'),array('href'=>"?id=$id&xls=1"));
			echo html_writer::end_tag('div');				
		}


		$view->display($report->extradata);	
	}else{

		echo html_writer::start_tag('div');
			echo html_writer::tag('span',get_string('noview','report_mega'));
		echo html_writer::end_tag('div');		
	}

if(empty($xls)){	
	echo html_writer::start_tag('div');
		echo html_writer::tag('a',get_string('goback','report_mega'),array('href'=>$link));
	echo html_writer::end_tag('div');	
	echo $OUTPUT->footer();
}




