<?php
/**
 * Capability definitions for the groupleader block.
 * @package    contributed
 * @subpackage blocks
 * @copyright  2011 Bas Brands (http://www.basbrands.nl)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/lib/formslib.php');

class user_mycodex_form extends moodleform {

	// Define the form
	function definition() {
		global $USER, $CFG, $COURSE;
		$mform =& $this->_form;
		//Accessibility: "Required" is bad legend text.
		$strgeneral  = get_string('userinfo','block_mycodex');
		$strrequired = get_string('required');

		$mform->addElement('header', 'moodle', $strgeneral);

        $editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES, 'noclean'=>true);
        $mform->addElement('editor', 'mycodextext', get_string('configcontent', 'block_html'), null, $editoroptions);
        $mform->addRule('mycodextext', null, 'required', null, 'client');
        $mform->setType('mycodextext', PARAM_RAW); 

		$this->add_action_buttons(false, 'Save text');
	}

	function definition_after_data() {
		global $USER, $CFG;

	}

	function validation($usernew, $files) {
		global $CFG;
		return true;
	}
}

?>
