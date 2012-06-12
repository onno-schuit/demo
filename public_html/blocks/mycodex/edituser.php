<?php

/**
 * Capability definitions for the groupleader block.
 * @package    contributed
 * @subpackage blocks
 * @copyright  2011 Bas Brands (http://www.basbrands.nl)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->libdir.'/gdlib.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/blocks/mycodex/editcodex_form.php');
require_once($CFG->dirroot.'/blocks/mycodex/editlib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');

$id = optional_param('id', -1, PARAM_INT); 
$currentuser = $USER;


$PAGE->set_url('/blocks/allcourses/allcoursespage.php');
$systemcontext = get_context_instance(CONTEXT_SYSTEM);
$PAGE->set_context($systemcontext);
$PAGE->set_pagelayout('standard');


if ($id != $currentuser->id) {
	// creating new user
	//print_error('wrong user id');
} else {
	profile_load_data($currentuser);
}


$currentuser->mycodextext = $currentuser->profile_field_codextext;

$userform = new user_mycodex_form();
$userform->set_data($currentuser);
$userform->is_validated();

if ($usernew = $userform->get_data()) {
	
	$currentuser->profile_field_codextext = $usernew->mycodextext;

	profile_save_data($currentuser);

	redirect($CFG->wwwroot . "/blog/index.php?userid={$USER->id}");
}


echo $OUTPUT->header();

$userform->display();

echo $OUTPUT->footer();

