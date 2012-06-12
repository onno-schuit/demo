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


defined('MOODLE_INTERNAL') || die();

/**
 * MyCollaborator Block
 *
 * @package    block
 * @subpackage mycollaborator
 * @copyright  2012 BrightAlley
 * @author     Bas Brands <bmbrands@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_mycollaborator extends block_base {
    /**
     * block initializations
     */
    public function init() {
        global $CFG, $COURSE;
        require_once($CFG->dirroot . '/blocks/mycollaborator/lib.php');
        $currentgroup = mycollaborator_get_active_group($COURSE->id);
        if ($group = groups_get_group($currentgroup) ){
            $this->title   = $group->name;
        } else {
            $this->title = 'MyCollaborator';
        }
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
        $group = groups_get_group($currentgroup);
        if ($group) {
            $this->content->text .= '<div id="mycollaboratorwrapper"><div id="mycdesc">'.$group->description.'</div></div>';
        } else {
            $this->content->text .= '<div id="mycollaboratorwrapper"><div id="myctitle">No Group selected</div><div id="mycdesc"></div></div>';
        }
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

         $form = new block_mycollaborator.phpConfigForm(null, array($this->config));
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
