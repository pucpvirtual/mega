<?php

defined('MOODLE_INTERNAL') || die;

$ADMIN->add('reports', new admin_externalpage('reportmega', get_string('pluginname', 'report_mega'), "$CFG->wwwroot/report/mega/index.php", 'report/mega:view'));

// no report settings
$settings = null;
