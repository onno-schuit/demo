<?php

/**
 * @author Bas Brands
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package moodle linkedIn auth
 *
 */

error_reporting(E_ALL);

require_once('../../config.php');

require_once($CFG->dirroot.'/auth/linkedin/linkedin_edituser_form.php');

$userid = optional_param('id', $USER->id, PARAM_INT);    // user id
$course = optional_param('course', SITEID, PARAM_INT);   // course id (defaults to Site)

$PAGE->set_url('/auth/linkedin/linkedin_edituser', array('course'=>$course, 'id'=>$userid));
$PAGE->set_pagelayout('linkedin');

if (!$user = $DB->get_record('user', array('id'=>$userid))) {
	print_error('invaliduserid');
}

$userform = new linkedin_edituser_form();

if ($usernew = $userform->get_data()) {
	add_to_log($course->id, 'user', 'update', "view.php?id=$user->id&course=$course->id", '');
	$DB->update_record('user', $usernew);

	$usernew = $DB->get_record('user', array('id'=>$user->id));
	if ($USER->id == $user->id) {
		// Override old $USER session variable if needed
		foreach ((array)$usernew as $variable => $value) {
			$USER->$variable = $value;
		}
	}
	events_trigger('user_updated', $usernew);
	$urltogo = $CFG->wwwroot.'/auth/linkedin/reloadhome.php';
	redirect($urltogo);
}

$PAGE->set_title("userform");
$PAGE->set_heading("userform");
$userform->set_data($user);
echo $OUTPUT->header();
echo get_string('forminfo','auth_linkedin');
$userform->display();
echo $OUTPUT->footer();
?>
