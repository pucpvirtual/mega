<?php

Class Mega_Model{

	private $db;


	public function Mega_Model(){
		global $DB;
		$this->db = $DB;
	}

	public function get_reports($userid){

		$data = $this->db->get_records('mega_report',array('userid'=>$userid),'name','id,name,state,timemodified');
		return $data;
	}

	public function get_report($id){

		$data = $this->db->get_record('mega_report',array('id'=>$id),'id,name,state,type,element,category,extradata');
		return $data;
	}

	public function get_quiz($id){
		$quiz = $this->db->get_record('quiz',array('id'=>$id),'id,name,questions,course');
		return $quiz;
	}

	public function quiz_attempts($id,$courseid){
		$sql = "SELECT qza.id
	    	    FROM {quiz_attempts} qza 
	        	INNER JOIN {role_assignments} ra ON qza.userid=ra.userid
	        	INNER JOIN {context} c ON ra.contextid=c.id
	        	WHERE qza.quiz = ? AND qza.state = ? AND ra.roleid = ? 
	        	AND c.contextlevel=  ? AND c.instanceid = ?";

		$params = array($id,'finished','5','50',$courseid);

    	$attempts=$DB->get_fieldset_sql($sql, $params);

    	return $attempts;

	}


	public function quiz_questions($id){

		$sql ="SELECT qa.questionid id, q.questiontext pregunta, count(*) respuestas
			   FROM  {quiz} qz
			   INNER JOIN  {quiz_attempts} qza ON qza.quiz = qz.id
			   INNER JOIN  {question_attempts} qa ON   qa.questionusageid = qza.uniqueid
			   INNER JOIN  {question} q ON   qa.questionid = q.id
			   WHERE qz.id = ?
			   GROUP BY qa.questionid
			   ORDER BY qa.questionid";

		$questions  = $this->db->get_records_sql($sql,array($id));

		return $questions;
	}

	public function get_answers($id,$qid){

        $sql = "SELECT qza.uniqueid, count(*) respuestas, qa.responsesummary respuesta,
                IF(qa.rightanswer=qa.responsesummary,'correcto','incorrecto') estado
                FROM  {quiz} qz 
                INNER JOIN  {quiz_attempts} qza ON qza.quiz = qz.id
                INNER JOIN  {question_attempts} qa ON  qa.questionusageid = qza.uniqueid
                WHERE qz.id = ?  AND qa.questionid = ?
                GROUP BY qa.responsesummary
                ORDER BY respuestas DESC";

        $answers = $this->db->get_records_sql($sql,array($id,$qid)); 

        return $answers;
	}

	public function get_coursexcat($cat){

		$courses = $this->db->get_records('course',array('category'=>$cat),'fullname','id,fullname');
		return $courses;		
	}

	public function get_coursexid($id){

		$courses = $this->db->get_records('course',array('id'=>$id),'fullname','id,fullname');
		return $courses;		
	}

	public function get_gradesxcs($ids){

		$result = array();

		$sql = "SELECT id,itemname,itemtype,itemmodule,courseid
				FROM {grade_items}
				WHERE  courseid IN ({$ids})
				ORDER BY courseid ASC, itemtype DESC, itemmodule DESC
				";
		
		$grades = $this->db->get_records_sql($sql,array());

		foreach($grades as $g){

			$result[$g->courseid][$g->id] = $g;
		}

		return $result;

	}

	public function get_userssxcs($ids,$t = 0){

		$result = array();

		if(empty($t)){
			$sql = "SELECT r.userid, u.username, u.firstname,u.lastname,u.email,u.lastaccess
					FROM {role_assignments} r 
					INNER JOIN {user} u ON u.id = r.userid
					INNER JOIN {context} ctx ON ctx.id = r.contextid AND contextlevel = ?
					INNER JOIN {course} c ON c.id = ctx.instanceid 
					WHERE r.roleid = ? AND c.id IN ($ids)
					GROUP BY r.userid
					";

			$result = $this->db->get_records_sql($sql,array('50','5'));
		}else{
			$sql = "SELECT c.id, GROUP_CONCAT(r.userid) users
					FROM {role_assignments} r 
					INNER JOIN {context} ctx ON ctx.id = r.contextid AND ctx.contextlevel = ?
					INNER JOIN {course} c ON c.id = ctx.instanceid 
					WHERE r.roleid = ? AND c.id IN ($ids)
					GROUP BY  c.id
					";

			$users = $this->db->get_records_sql($sql,array('50','5'));			


			foreach($users as $c){

				$result[$c->id] = explode(',', $c->users);
			}
		}


		return $result;
	}

	public function get_ugrades($ids){

		$result = array();

		$sql = "SELECT id,userid,itemid,finalgrade
				FROM {grade_grades}
				WHERE itemid IN ($ids) 
				ORDER BY userid
				";

		$grades = $this->db->get_records_sql($sql,array());

		foreach($grades as $g){
			$result[$g->userid][$g->itemid] = $g->finalgrade;
		}

		return $result;

	}

	public function get_scorm($id){

		$result = $this->db->get_record('scorm',array('id'=>$id),'id,name,course');

		return $result;
	}

	public function get_userxscorm($id){

		$sql = "SELECT sst.userid, u.username, u.firstname,u.lastname, GROUP_CONCAT(sst.element) element,GROUP_CONCAT(sst.value) value
				FROM {scorm_scoes_track} sst 
				INNER JOIN {user} u ON u.id = sst.userid
				WHERE sst.scormid = ? AND sst.attempt = ?
				GROUP BY sst.userid
				";

		$users = $this->db->get_records_sql($sql,array($id,1));

		return $users;

	}

}


 