<?php 

class Mega_grade{

	private $model;
	private $courses;
	private $items;
	private $grades;
	private $users;

	public function Mega_grade($model){
		$this->model = $model;
	}

	public function generate($id,$category){

		$temp = array();

		if($id == '0'){
			$this->courses = $this->model->get_coursexcat($category);
		}else{
			$this->courses = $this->model->get_coursexid($id);
		}

		foreach($this->courses as $c){
			$temp[] = $c->id;
		}

		$ids = implode(',',$temp);

		$this->items = $this->model->get_gradesxcs($ids);

		$this->users = $this->model->get_userssxcs($ids);

		$this->usersxc = $this->model->get_userssxcs($ids,1);

		$temp = array();

		foreach($this->items as $c){
			foreach($c as $g){
				$temp[] = $g->id;
			}
			
		}

		$ids = implode(',', $temp);

		$this->grades = $this->model->get_ugrades($ids);

	}

	public function display($extradata = null){

		$data = (array)json_decode($extradata);
		$tdata = array();
		foreach ($data as $k => $v) {
			$tdata[(int)$k] = (int)$v;
		}

		$data = $tdata;
		$items = '';

		echo html_writer::start_tag('table');

			echo html_Writer::start_tag('thead');

				echo html_writer::start_tag('tr');

					echo html_writer::tag('td',get_string('userdata','report_mega'),array('colspan'=>5));

					$items .= html_writer::tag('td',get_string('username'));
					$items .= html_writer::tag('td',get_string('firstname'));
					$items .= html_writer::tag('td',get_string('lastname'));
                    $items .= html_writer::tag('td',get_string('email'));
                    $items .= html_writer::tag('td',get_string('lastaccess'));

					foreach($this->courses as $c){

						$span = count($this->items[$c->id]);

						echo html_writer::tag('td',$c->fullname,array('colspan'=>$span));

						foreach($this->items[$c->id] as $g){

							if($g->itemtype == 'course'){
								$items .= html_writer::tag('td',get_string('finalgrade','report_mega'));	
							}else{
								$items .= html_writer::tag('td',$g->itemname);
							}
							
						}

					}

				echo html_writer::end_tag('tr');

				echo html_writer::start_tag('tr');

					echo $items;

				echo html_writer::end_tag('tr');

			foreach($this->users as $u){
				$u->lastaccess = (empty($u->lastaccess)) ? '-' : date('d-m-Y H:i',$u->lastaccess);  
				echo html_writer::start_tag('tr');

					echo html_writer::tag('td',$u->username);
					echo html_writer::tag('td',$u->firstname);
					echo html_writer::tag('td',$u->lastname);
                    echo html_writer::tag('td',$u->email);
                    echo html_writer::tag('td',$u->lastaccess);

					foreach($this->courses as $c){

						if(in_array($u->userid,$this->usersxc[$c->id])){
							foreach($this->items[$c->id] as $g){

								if($g->itemmodule == 'scorm'){
										if(array_key_exists($g->id, $data)){

											switch ($data[$g->id]) {
												case '0':
													if(isset($this->grades[$u->userid][$g->id])) echo html_writer::tag('td',number_format($this->grades[$u->userid][$g->id],2,'.',''));
													else echo html_writer::tag('td', '-');
													break;													
													break;
												case '1':
													if(isset($this->grades[$u->userid][$g->id])) echo html_writer::tag('td',get_string('doit','report_mega'));
													else echo html_writer::tag('td',get_string('dontit','report_mega'));
													break;
												case '2':
													if(isset($this->grades[$u->userid][$g->id])) echo html_writer::tag('td', ($this->grades[$u->userid][$g->id]*5).'%');
													else echo html_writer::tag('td','0%');													
													break;
												default:
													break;
											}

										}else{
											if(isset($this->grades[$u->userid][$g->id])) echo html_writer::tag('td',get_string('doit','report_mega'));
											else echo html_writer::tag('td',get_string('dontit','report_mega'));										
										}

								}else if($g->itemmodule == 'questionnaire' ){

										if(isset($this->grades[$u->userid][$g->id])) echo html_writer::tag('td',get_string('doit','report_mega'));
										else echo html_writer::tag('td',get_string('dontit','report_mega'));

								}else{

										if(isset($this->grades[$u->userid][$g->id])) echo html_writer::tag('td',number_format($this->grades[$u->userid][$g->id],2,'.',''));
										else echo html_writer::tag('td','-');
								}
								
							}
						}else{
							foreach($this->items[$c->id] as $g){
								echo html_writer::tag('td',get_string('noincourse','report_mega'));
							}							
						}


					}

				echo html_writer::end_tag('tr');				

			}


			echo html_Writer::end_tag('thead');


		echo html_writer::end_tag('table');


	}
	
}


