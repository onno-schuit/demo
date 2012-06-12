<?php

include_once(dirname(__FILE__) . '/../../../../config.php');

$config = array();
$config['db'] = 'mysql';
$config['mysql.host'] = $CFG->dbhost;
$config['mysql.db'] = $CFG->dbname;
$config['mysql.user'] = $CFG->dbuser;
$config['mysql.password'] = $CFG->dbpass;
$config['prefix'] = 'mdl_activitylist_';
$config['url'] = '';
$config['mtt_url'] = $CFG->wwwroot . '/mod/activitylist/mtt/';
$config['title'] = 'THNKLink Activitylist';
$config['lang'] = 'en';
$config['password'] = '';
$config['smartsyntax'] = 1;
$config['timezone'] = 'Europe/Amsterdam';
$config['autotag'] = 1;
$config['duedateformat'] = 1;
$config['firstdayofweek'] = 1;
$config['session'] = 'default';
$config['clock'] = 24;
$config['dateformat'] = 'j M Y';
$config['dateformat2'] = 'n/j/y';
$config['dateformatshort'] = 'j M';
$config['template'] = 'default';
$config['showdate'] = 0;
?>