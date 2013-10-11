<?php

require_once($CFG->libdir.'/formslib.php');

class report_mega_new extends moodleform {

    function definition() {
        global $DB;

        $categorys  = $DB->get_records_menu('course_categories',array('parent'=>0));
        asort($categorys);

        $categorys = array_merge(array(0=>get_string('pick','report_mega')), $categorys);

        $types = array(get_string('pick','report_mega'),get_string('coursecat','report_mega'),get_string('exam','report_mega'),get_string('scorm','report_mega'));

        $mform =& $this->_form;

        $mform->addElement('header', 'new', get_string('new','report_mega'));

        $mform->addElement('text','name', get_string('reportname','report_mega'));

        $mform->addElement('select','type',get_string('reportype','report_mega'),$types);

        $mform->addElement('header', 'wrapcat', get_string('courseacat','report_mega'));

        $mform->addElement('select','category',get_string('category'),$categorys, array('id'=>'cat-0', 'class'=>'selectanid', 'rel'=>'.wrapcat2'));
       
        $mform->addElement('html','<div class="wrapcat2"></div>');

        $mform->addElement('html','<div class="wapcontentcourse wrapdata"></div>');

        $mform->addElement('header', 'wrapexam',get_string('exam','report_mega'));

        $mform->addElement('html','<div class="wapcontentexam wrapdata"></div>');

        $mform->addElement('header', 'wrapscorm', get_string('scorm','report_mega'));

        $mform->addElement('html','<div class="wapcontentscorm wrapdata"></div>');

        $mform->addElement('header', 'wrappoll', get_string('poll','report_mega'));

        $mform->addElement('html','<div class="wapcontentpoll wrapdata"></div>');

        $mform->addElement('header', 'finish', get_string('finish','report_mega'));

        $mform->addElement('submit','submit', get_string('create','report_mega'));
      }
}


