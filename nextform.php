<?php

include('../../config.php');

if(isset($_POST['cat'])){

	if($_POST['cat'] == '0'){
		echo '';
		exit;
	}

	$cat = $_POST['cat'];

	$categories = $DB->get_records_menu('course_categories',array('parent'=>$cat),'name','id,name');
	$courses = $DB->get_records_menu('course',array('category'=>$cat),'fullname','id,fullname');

	echo html_writer::empty_tag('input',array('type'=>'hidden','value'=>$cat,'name'=>'tempcat'));

	if(count($categories) > 0){
		echo html_writer::tag('label',get_string('category'),array('for'=>'cat-'.$cat));
		echo html_writer::start_tag('select',array('name'=>'category','id'=>'cat-'.$cat, 'class'=>'selectanid', 'rel'=>'.wrapcat'.$cat));

			echo html_writer::tag('option',get_string('pick'),array('value'=>'0'));

			foreach($categories as $k => $v){
				echo html_writer::tag('option',$v,array('value'=>$k));
			}

		echo html_writer::end_tag('select');

	}

	echo html_writer::start_tag('div',array('class'=>'wrapcat'.$cat));

	if(count($courses) > 0){

		echo html_writer::start_tag('div',array('wrapcourse'));
			echo html_writer::tag('label',get_string('course'),array('for'=>'course'));
			echo html_writer::start_tag('select',array('name'=>'course','class'=>'course','id'=>'course'));

				echo html_writer::tag('option',get_string('allcourse','report_mega'),array('value'=>'0'));

				foreach($courses as $k => $v){
					echo html_writer::tag('option',$v,array('value'=>$k));
				}

			echo html_writer::end_tag('select');
		echo html_writer::end_tag('div');
	}

	if(count($courses) == 0 && count($categories) == 0) echo html_writer::tag('span', get_string('nocoursedata','report_mega'));

	echo html_writer::end_tag('div');
}

if(isset($_POST['course'])){

	if($_POST['course'] == '0'){

		if($_POST['type'] != '1'){
			echo get_string('pickcourse','report_mega');
			exit;	
		}

		if($_POST['category'] == '0'){
			exit;
		}

		$sql_scorm = "SELECT g.id, g.itemname FROM 
					  {grade_items} g INNER JOIN {course} c  ON g.courseid  = c.id
					  WHERE c.category = ?  AND g.itemmodule = ?";
		$params_scorm = array($_POST['category'],'scorm');

		$elements = $DB->get_records_sql_menu($sql_scorm,$params_scorm);

		if(count($elements) > 0){
			
			foreach($elements as $k => $v){
				echo html_writer::start_tag('div');
					echo html_writer::tag('label',$v,array('for'=>'tscorm[]'));

					echo html_writer::start_tag('select',array('name'=>'tscorm['.$k.']','id'=>'tscorm['.$k.']'));
						echo html_writer::tag('option','Calificación',array('value'=>'0'));
						echo html_writer::tag('option','Participación',array('value'=>'1'));
						echo html_writer::tag('option','Seguimiento',array('value'=>'2'));
					echo html_writer::end_tag('select');

				echo html_writer::end_tag('div');
			}

		}
		exit;
	}


	$table = ($_POST['type'] == '2')? 'quiz' : 'scorm';

	$elements = $DB->get_records_menu($table,array('course'=>$_POST['course']),'name','id,name');

	if(count($elements) > 0){

		if($_POST['type']!= 1){

			echo html_writer::tag('label',$table,array('for'=>'element'));
			echo html_writer::start_tag('select',array('name'=>'element','id'=>'element'));

				echo html_writer::tag('option',get_string('pick','report_mega'),array('value'=>'0'));

				foreach($elements as $k => $v){
					echo html_writer::tag('option',$v,array('value'=>$k));
				}

			echo html_writer::end_tag('select');	
		}else{
			foreach($elements as $k => $v){
				echo html_writer::start_tag('div');
					echo html_writer::tag('label',$v,array('for'=>'tscorm[]'));

					echo html_writer::start_tag('select',array('name'=>'tscorm['.$k.']','id'=>'tscorm['.$k.']'));
						echo html_writer::tag('option','Calificación',array('value'=>'0'));
						echo html_writer::tag('option','Participación',array('value'=>'1'));
						echo html_writer::tag('option','Seguimiento',array('value'=>'2'));
					echo html_writer::end_tag('select');

				echo html_writer::end_tag('div');
			}
		}




	}else{
		echo get_string('haszero','report_mega');
		exit;
	}


}