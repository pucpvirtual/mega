<?php

include('../../config.php');
include('model.php');

$model = new Mega_Model;

$PAGE->set_url('/report/mega/index.php');

require_login();

$context = get_context_instance(CONTEXT_SYSTEM);

$name = get_string('front','report_mega');

$PAGE->set_context($context);

$PAGE->navbar->add($name);

$PAGE->set_title($name);

$PAGE->set_heading($name);


$reports = $model->get_reports($USER->id);

echo $OUTPUT->header();
	$link = new moodle_url('/report/mega/create.php');
	if(count($reports) > 0){

		echo html_writer::start_tag('div');

			echo html_writer::start_tag('div');
				echo html_writer::tag('a',get_string('newreport','report_mega'),array('href'=>$link));
			echo html_writer::end_tag('div');

			echo html_writer::start_tag('table');
				echo html_writer::start_tag('tr');
					echo html_writer::tag('td',get_string('reportname','report_mega'));
					echo html_writer::tag('td',get_string('reportstate','report_mega'));
					echo html_writer::tag('td','',array('colspan'=>'2'));
				echo html_writer::end_tag('tr');

		foreach($reports as $r){

			$r->state = ($r->state == 0)? get_string('inactive','report_mega'):get_string('active','report_mega');

			$edit = new moodle_url('/report/mega/edit.php',array('id'=>$r->id));
			$delete = new moodle_url('/report/mega/delete.php',array('id'=>$r->id));
			$view = new moodle_url('/report/mega/view.php',array('id'=>$r->id));
			$edit = html_writer::tag('a',get_string('edit','report_mega'),array('href'=>$edit));
			$view = html_writer::tag('a',get_string('view','report_mega'),array('href'=>$view));
			$delete = html_writer::tag('a',get_string('delete','report_mega'),array('href'=>$delete));

				echo html_writer::start_tag('tr');
					echo html_writer::tag('td',$r->name);
					echo html_writer::tag('td',$r->state);
					echo html_writer::tag('td',$view);
					//echo html_writer::tag('td',$edit);
					echo html_writer::tag('td',$delete);
				echo html_writer::end_tag('tr');
		}
			echo html_writer::end_tag('table');
	}else{

		echo html_writer::start_tag('div');

			echo html_writer::tag('span',get_string('notyet','report_mega'));

			echo html_writer::start_tag('div');
				echo html_writer::tag('a',get_string('newreport','report_mega'),array('href'=>$link));
			echo html_writer::end_tag('div');

		echo html_writer::end_tag('div');

	}

echo $OUTPUT->footer();

