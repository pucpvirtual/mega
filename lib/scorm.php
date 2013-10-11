<?php 

class Mega_scorm{

	private $model;
	private $users;
	
	public function Mega_scorm($model){
		$this->model = $model;		
	}

	public function generate($id,$category){
		$this->users = $this->model->get_userxscorm($id);
		$this->scorm = $this->model->get_scorm($id);
		$this->musers = $this->model->get_userssxcs($this->scorm->course);
	}

	public function display($extradata = null){

		$statuslist = array('completed'=>get_string('complete','report_mega'),'incomplete'=>get_string('incomplete','report_mega'));

		echo html_writer::start_tag('table');

			echo html_Writer::start_tag('thead');

				echo html_writer::start_tag('tr');

					echo html_writer::tag('td',get_string('userdata','report_mega'),array('colspan'=>3));

					echo html_writer::tag('td','',array('colspan'=>3));
	
				echo html_writer::end_tag('tr');

				echo html_writer::start_tag('tr');

					echo html_writer::tag('td',get_string('username'));
					echo html_writer::tag('td',get_string('firstname'));
					echo html_writer::tag('td',get_string('lastname'));

					echo html_writer::tag('td',get_string('startdate','report_mega'));
					echo html_writer::tag('td',get_string('starthour','report_mega'));
					echo html_writer::tag('td',get_string('totaltime','report_mega'));
					echo html_writer::tag('td',get_string('scormstate','report_mega'));
					echo html_writer::tag('td',get_string('scormgrade','report_mega'));

				echo html_writer::end_tag('tr');

			$suser = array();

			foreach($this->users as $u){

				$element = explode(',',$u->element);
				$value = explode(',',$u->value);
				$data = array_combine($element,$value);
				$suser[] = $u->userid;

				echo html_writer::start_tag('tr');

					echo html_writer::tag('td',$u->username);
					echo html_writer::tag('td',$u->firstname);
					echo html_writer::tag('td',$u->lastname);

					if(isset($data['x.start.time'])){
						echo html_writer::tag('td',date('d-m-Y',$data['x.start.time']));
						echo html_writer::tag('td',date('H:i:s',$data['x.start.time']));
					}else{
						echo html_writer::tag('td','-');
						echo html_writer::tag('td','-');	
					}

					if(isset($data['cmi.core.total_time'])) echo html_writer::tag('td',$data['cmi.core.total_time']);
					else echo html_writer::tag('td','-'); 					
					
					if(isset($data['cmi.core.lesson_status'])) echo html_writer::tag('td',$statuslist[$data['cmi.core.lesson_status']]);
					else echo html_writer::tag('td','-'); 

					if(isset($data['cmi.core.score.raw'])) echo html_writer::tag('td',$data['cmi.core.score.raw']);
					else echo html_writer::tag('td','-'); 

				echo html_writer::end_tag('tr');				
			}

			foreach($this->musers as $u){
				if(in_array($u->userid,$suser)) continue;
				echo html_writer::start_tag('tr');

					echo html_writer::tag('td',$u->username);
					echo html_writer::tag('td',$u->firstname);
					echo html_writer::tag('td',$u->lastname);
					echo html_writer::tag('td','-'); 
					echo html_writer::tag('td','-');
					echo html_writer::tag('td','-');
					echo html_writer::tag('td','-');
					echo html_writer::tag('td','-');

				echo html_writer::end_tag('tr');				

			}

			echo html_Writer::end_tag('thead');

		echo html_writer::end_tag('table');
	}
	
}

