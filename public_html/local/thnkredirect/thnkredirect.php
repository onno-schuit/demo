<?php
function redirect_if_thnkuser($request) {
	global $CFG;
	$userlist = json_decode(file_get_contents($CFG->dataroot.'/userlist.dat'), true);
	$firstlast = array();
	foreach($userlist as $user=>$redirect) {
        if ('/'.$user == $request ) {
        	redirect($redirect);
        }
	}
}