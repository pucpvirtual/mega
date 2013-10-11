<?php 

class Mega_quiz{
	
	private $model;

	private $quiz;

	private $questions;

	public function Mega_quiz($model){
		$this->model = $model;		
	}

	public function generate($id,$category){

		$this->quiz = $this->model->get_quiz($id);

		$this->questions = $this->model->quiz_questions($id);

		foreach($this->questions as $k => $q){

			$this->questions[$k]->answer = $this->model->get_answers($id,$q->id);

		}
		
	}

	public function display($extradata = null){

		echo html_writer::start_tag('table');

			foreach ($this->questions as $k => $q) {

				echo html_writer::start_tag('tr');
					echo html_writer::tag('td',$q->pregunta);
					echo html_writer::tag('td',get_string('total','report_mega'). $q->respuestas);
				echo html_writer::end_tag('tr');

				foreach($q->answer as $r){

					$r->respuesta = (empty($r->respuesta) || trim($r->respuesta) == "" )? get_string('noanswer','report_mega') : $r->respuesta;

					echo html_writer::start_tag('tr',array('class'=>'answer '.$r->estado));
						echo html_writer::tag('td',$r->respuesta);
						echo html_writer::tag('td', $r->respuestas);
					echo html_writer::end_tag('tr');				
				}
				
			}

		echo html_writer::end_tag('table');
	}


}

