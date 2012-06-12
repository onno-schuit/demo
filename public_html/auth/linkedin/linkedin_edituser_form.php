<?php

/**
 * @author Bas Brands
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package moodle linkedIn auth
 *
 */

require_once($CFG->dirroot.'/lib/formslib.php');

class linkedin_edituser_form extends moodleform {

	// Define the form
	function definition() {
		
		
		global $USER, $CFG, $COURSE;
		
		$mform =& $this->_form;
		$strgeneral  = get_string('linkedindetails','auth_linkedin');
		$strrequired = get_string('required');


		$mform->addElement('header', 'moodle', $strgeneral);

		$mform->addElement('hidden', 'id');
		$mform->addElement('hidden', 'course', $COURSE->id);
		$mform->addElement('hidden', 'username');
		
		$mform->addElement('static', 'firstname',get_string('firstname'),$USER->firstname);
		$mform->addElement('static', 'lastname',get_string('lastname'),$USER->lastname);
		
		$mform->addElement('hidden', 'institution', $USER->institution);
		$mform->addElement('hidden', 'city', $USER->city);
		$mform->addElement('hidden', 'country', $USER->country);
		$mform->addElement('hidden', 'description', $USER->description);
		
		$mform->addElement('text', 'email', get_string('email'), 'maxlength="100" size="30"',$USER->email);
		$mform->setType('email', PARAM_NOTAGS);

		$mform->addElement('hidden', 'maildisplay','2');
		$mform->addElement('hidden', 'auth','linkedin');
		$mform->addElement('hidden', 'preference_auth_forcepasswordchange','0');
		$mform->addElement('hidden', 'emailenable','1');
		$mform->addElement('hidden', 'mailformat','1');
		$mform->addElement('hidden', 'maildigest','0');
		$mform->addElement('hidden', 'autosubscribe','1');
		$mform->addElement('hidden', 'autosubscribe','0');
		$mform->addElement('hidden', 'htmleditor','1');
		$mform->addElement('hidden', 'ajaxdisabled','0');
		$mform->addElement('hidden', 'screenreader','0');
		$mform->addElement('hidden', 'timezone','99');
		$mform->addElement('hidden', 'lang', $CFG->lang);
		$mform->setDefault('lang', 'en_utf8');

		$this->add_action_buttons(false, get_string('updatemyprofile'));
	}

}

?>
