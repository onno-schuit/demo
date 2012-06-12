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

class block_myprofile_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;
        $mform->addElement('header', 'configheader', get_string('myprofile_settings', 'block_myprofile'));

        $mform->addElement('selectyesno', 'config_display_picture', get_string('display_picture', 'block_myprofile'));
        if (isset($this->block->config->display_picture)) {
            $mform->setDefault('config_display_picture', $this->block->config->display_picture);
        } else {
            $mform->setDefault('config_display_picture', '1');
        }


    }
}