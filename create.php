<?php

include('../../config.php');
include('forms.php');
include('model.php');

$model = new Mega_Model;

$PAGE->set_url('/report/mega/create.php');

require_login();

$context = get_context_instance(CONTEXT_SYSTEM);

if(!is_siteadmin()){
    redirect(new moodle_url('/report/mega/index.php',array()));
}

$name = get_string('create','report_mega');

$PAGE->set_context($context);

$PAGE->navbar->add($name);

$PAGE->set_title($name);

$PAGE->set_heading($name);

$PAGE->requires->js('/report/mega/js/jquery.js');
$PAGE->requires->js('/report/mega/js/lib.js');

$mform = new report_mega_new;

echo $OUTPUT->header();
    
if(isset($_POST) && count($_POST) > 0){

	$data = (object)$_POST;

    if(empty($_POST['type'])){
        echo html_writer::tag('div',get_string('errorparameter','report_mega'));
    }

    if(isset($_POST['tscorm'])){
        $tscorm = json_encode($_POST['tscorm']) ;
    }else{
        $tscorm = NULL;
    }

    $data->category = ($data->category != '0')? $data->category : $data->tempcat;

    $data->element = (isset($data->element))? $data->element : $data->course;

    $record = array('name'=>$data->name,
    				'userid'=>$USER->id,
    				'category'=>$data->category,
    				'type'=>$data->type,
    				'element'=>$data->element,
    				'state'=>1,
                    'extradata' => $tscorm,
    				'timemodified'=>strtotime(date('d-m-Y H:i:s')));	

    $DB->insert_record('mega_report',$record);

    $blink = new moodle_url('/report/mega/index.php');

    echo html_writer::tag('div',get_string('goodsave','report_mega'),array('class'=>'exit-save'));

    echo html_writer::tag('a',get_string('goback','report_mega'),array('href'=>$blink,'class'=>'exit-link-save'));

}else{
    $mform->display();
}

echo $OUTPUT->footer();
