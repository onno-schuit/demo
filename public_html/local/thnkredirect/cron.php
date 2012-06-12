<?php

echo 'running local/thnkredirect cron';
$userlist = $DB->get_records_select('user','deleted=0');

$firstlast = array();
$url = $CFG->wwwroot . '/blog/index.php?userid=';

foreach($userlist as $user) {
	if (empty($user->firstname)) {
		continue;
	}
	$userfirstlast = strtolower($user->firstname . $user->lastname);
	$userfirstlast = preg_replace('/[^a-zA-Z0-9\-]/', '', $userfirstlast);
	$userfirstlast = preg_replace('/^[\-]+/', '', $userfirstlast);
	$userfirstlast = preg_replace('/[\-]+$/', '', $userfirstlast);
	$userfirstlast = preg_replace('/[\-]{2,}/', ' ', $userfirstlast);
	$firstlast[$userfirstlast] = $url . $user->id;
	if ('/'.$userfirstlast == $request ) {
		redirect($url . $user->id);
	}
}
file_put_contents($CFG->dataroot.'/userlist.dat', json_encode($firstlast));
?>