<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Block displaying information about current logged-in user.
 *
 * This block can be used as anti cheating measure, you
 * can easily check the logged-in user matches the person
 * operating the computer.
 *
 * @package    block
 * @subpackage myprofile
 * @copyright  2010 Remote-Learner.net
 * @author     Olav Jordan <olav.jordan@remote-learner.ca>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Displays the current user's profile information.
 *
 * @copyright  2010 Remote-Learner.net
 * @author     Olav Jordan <olav.jordan@remote-learner.ca>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_mygroups extends block_base {
	/**
	 * block initializations
	 */
	public function init() {
		$this->title   = get_string('pluginname', 'block_mygroups');
	}

	/**
	 * block contents
	 *
	 * @return object
	 */
	public function get_content() {
		global $CFG, $USER, $DB, $OUTPUT, $PAGE;

		require_once($CFG->dirroot . '/blocks/mycollaborator/lib.php');

		if ($this->content !== NULL) {
			return $this->content;
		}

		if (!isloggedin() or isguestuser()) {
			return '';      // Never useful unless you are logged in as real users
		}

		$this->content = new stdClass;
		$this->content->text = '';
		$this->content->footer = '';

		$course = $this->page->course;

		$currentgroup = mycollaborator_get_active_group($course->id);
		$groupmembers = groups_get_members($currentgroup);

		$contacts = array();
		$dbcontacts = $DB->get_records('message_contacts',array('userid'=>$USER->id));
		foreach($dbcontacts as $id => $dbcontact)
			$contacts[] = $dbcontact->contactid;

		$this->content->text .= '<script type="text/javascript" src="'.$CFG->wwwroot.'/mod/connector/qtip2/jquery.qtip.min.js"></script>';
		foreach ($groupmembers as $groupmember) {
			/* create hidden div (balloon popup) */
        		$connect = "";
			 if (!in_array($groupmember->id, $contacts)) {
       			     $connect = "<a class='connector_connect' href='{$CFG->wwwroot}/message/index.php?user2={$groupmember->id}&viewing=contacts&addcontact={$groupmember->id}&sesskey=" . sesskey() . "'>" . get_string('connect', 'connector') . "</a>";
        		}
        		$description = '';
        		if (trim($groupmember->description) != '') $description = "<p class='connector_description'>{$groupmember->description}</p>";
        		$this->content->text .= '<div class="connector_popup" id="popup_for_userid_'.$groupmember->id.'">';
			$this->content->text .= $description;
        		$this->content->text .= '<div class="connector_fullname"><strong></strong>'.$groupmember->firstname.' '.$groupmember->lastname.'</strong></strong></div>';
			$this->content->text .= $connect.'<br>';
			$this->content->text .= '<a href="'.$CFG->wwwroot.'/message/index.php?id='.$groupmember->id.'">'.get_string('sendmessage', 'connector').'</a></div>';
			/* add image to page */
			$this->content->text .= '<div userid="'.$groupmember->id.'" class="mygroupmember">';
			$this->content->text .= '<a href="'. $CFG->wwwroot .'/blog/index.php?userid=' . $groupmember->id . '">';
			$this->content->text .= $OUTPUT->user_picture($groupmember, array('courseid'=>$course->id, 'size'=>'60', 'class'=>'profilepicture','link'=>false));
			$this->content->text .= '</a></div>';
		}

		$this->content->text .= <<<EOT
		<script type="text/javascript">
            	$(document).ready(function() {
                	$('.mygroupmember').each(function(index) {
				$(this).qtip({
                    			content: $('#popup_for_userid_'+$(this).attr('userid')),
                    			position: {
                        			my: 'bottom center',
                       		 		at: 'center'
                    			},
                    			hide: {
                        			fixed: true
                    			}
				});
                	});
           	});
        	</script>
EOT;

		return $this->content;
	}

	/**
	 * allow the block to have a configuration page
	 *
	 * @return boolean
	 */
	public function has_config() {
		return false;
	}

	/**
	 * allow more than one instance of the block on a page
	 *
	 * @return boolean
	 */
	public function instance_allow_multiple() {
		//allow more than one instance on a page
		return false;
	}

	/**
	 * allow instances to have their own configuration
	 *
	 * @return boolean
	 */
	function instance_allow_config() {
		//allow instances to have their own configuration
		return false;
	}

	/**
	 * instance specialisations (must have instance allow config true)
	 *
	 */
	public function specialization() {
	}

	/**
	 * displays instance configuration form
	 *
	 * @return boolean
	 */
	function instance_config_print() {
		return false;

		/*
		 global $CFG;

		 $form = new block_mygroups.phpConfigForm(null, array($this->config));
		 $form->display();

		 return true;
		 */
	}

	/**
	 * locations where block can be displayed
	 *
	 * @return array
	 */
	public function applicable_formats() {
		return array('all'=>true);
	}

	/**
	 * post install configurations
	 *
	 */
	public function after_install() {
	}

	/**
	 * post delete configurations
	 *
	 */
	public function before_delete() {
	}

}
