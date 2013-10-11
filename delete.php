<?php

include('../../config.php');

$id = required_param('id',PARAM_INT);


if(empty($id)){

	$PAGE->set_url('/report/mega/delete.php');

	require_login();

	$context = get_context_instance(CONTEXT_SYSTEM);

	$name = get_string('deletereport','report_mega');
	$PAGE->set_context($context);
	$PAGE->navbar->add($name);
	$PAGE->set_title($name);
	$PAGE->set_heading($name);

	echo $OUTPUT->header();
	 echo html_writer::tag('div',get_string('errorparameter','report_mega'));
	echo $OUTPUT->footer();

}

$DB->delete_records('mega_report', array('id'=>$id));
redirect(new moodle_url('/report/mega/index.php',array()));
