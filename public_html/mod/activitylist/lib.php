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
 * Library of interface functions and constants for module newmodule
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 * All the newmodule specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod
 * @subpackage newmodule
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
/** example constant */
//define('NEWMODULE_ULTIMATE_ANSWER', 42);

////////////////////////////////////////////////////////////////////////////////
// Moodle core API                                                            //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the information on whether the module supports a feature
 *
 * @see plugin_supports() in lib/moodlelib.php
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function activitylist_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_INTRO:         return true;
        default:                        return null;
    }
}

/**
 * Saves a new instance of the newmodule into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $newmodule An object from the form in mod_form.php
 * @param mod_newmodule_mod_form $mform
 * @return int The id of the newly inserted newmodule record
 */
function activitylist_add_instance(stdClass $newmodule, mod_activitylist_mod_form $mform = null) {
    global $DB;

    $newmodule->timecreated = time();

    # You may have to add extra stuff in here #

    $id = $DB->insert_record('activitylist', $newmodule);
    
    $moduleinstance_id = $DB->get_field_sql("SELECT cm.id FROM {course_modules} cm
        INNER JOIN {modules} m ON (cm.module=m.id AND m.name='activitylist')
        WHERE cm.instance=" . (int)$id); 
    $new_ow = $DB->get_field_sql('SELECT MAX(ow) AS max_ow FROM {activitylist_lists}');
    $new_ow += 1; 
    require_once('mtt/common.php');
    $new_list_record = array(
        'uuid' => generateUUID(),
        'instanceid' => $id,
        'name' => $newmodule->name,
        'ow' => $new_ow,
        'd_created' => time(),
        'd_edited' => time()
    );
    $DB->insert_record('activitylist_lists', $new_list_record); 
    
    return $id;
}

/**
 * Updates an instance of the newmodule in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $newmodule An object from the form in mod_form.php
 * @param mod_newmodule_mod_form $mform
 * @return boolean Success/Fail
 */
function activitylist_update_instance(stdClass $newmodule, mod_newmodule_mod_form $mform = null) {
    global $DB;

    $newmodule->timemodified = time();
    $newmodule->id = $newmodule->instance;

    # You may have to add extra stuff in here #

    return $DB->update_record('activitylist', $newmodule);
}

/**
 * Removes an instance of the newmodule from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function activitylist_delete_instance($id) {
    global $DB;

    if (! $newmodule = $DB->get_record('activitylist', array('id' => $id))) {
        return false;
    }

    $moduleinstance_id = $DB->get_field_sql("SELECT cm.id FROM {course_modules} cm
        INNER JOIN {modules} m ON (cm.module=m.id AND m.name='activitylist')
        WHERE cm.instance=" . (int)$id); 

    $q="DELETE l, t 
        FROM {activitylist_lists} l
        INNER JOIN {activitylist_todolist} t ON (l.id=t.list_id)
        WHERE l.instanceid=" . (int)$id;
    # delete lists and todo-items associated with this instance
    $DB->execute($q);
        
    # delete the instance itself 
    $DB->delete_records('activitylist', array('id' => $newmodule->id));

    return true;
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return stdClass|null
 */
function activitylist_user_outline($course, $user, $mod, $newmodule) {

    $return = new stdClass();
    $return->time = 0;
    $return->info = '';
    return $return;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @param stdClass $course the current course record
 * @param stdClass $user the record of the user we are generating report for
 * @param cm_info $mod course module info
 * @param stdClass $newmodule the module instance record
 * @return void, is supposed to echp directly
 */
function activitylist_user_complete($course, $user, $mod, $newmodule) {
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in newmodule activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @return boolean
 */
function activitylist_print_recent_activity($course, $viewfullnames, $timestart) {
    return false;  //  True if anything was printed, otherwise false
}

/**
 * Prepares the recent activity data
 *
 * This callback function is supposed to populate the passed array with
 * custom activity records. These records are then rendered into HTML via
 * {@link activitylist_print_recent_mod_activity()}.
 *
 * @param array $activities sequentially indexed array of objects with the 'cmid' property
 * @param int $index the index in the $activities to use for the next record
 * @param int $timestart append activity since this time
 * @param int $courseid the id of the course we produce the report for
 * @param int $cmid course module id
 * @param int $userid check for a particular user's activity only, defaults to 0 (all users)
 * @param int $groupid check for a particular group's activity only, defaults to 0 (all groups)
 * @return void adds items into $activities and increases $index
 */
function activitylist_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid=0, $groupid=0) {
}

/**
 * Prints single activity item prepared by {@see activitylist_get_recent_mod_activity()}

 * @return void
 */
function activitylist_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function activitylist_cron () {
    return true;
}

/**
 * Returns an array of users who are participanting in this newmodule
 *
 * Must return an array of users who are participants for a given instance
 * of newmodule. Must include every user involved in the instance,
 * independient of his role (student, teacher, admin...). The returned
 * objects must contain at least id property.
 * See other modules as example.
 *
 * @param int $newmoduleid ID of an instance of this module
 * @return boolean|array false if no participants, array of objects otherwise
 */
function activitylist_get_participants($newmoduleid) {
    return false;
}

/**
 * Returns all other caps used in the module
 *
 * @example return array('moodle/site:accessallgroups');
 * @return array
 */
function activitylist_get_extra_capabilities() {
    return array();
}

////////////////////////////////////////////////////////////////////////////////
// Gradebook API                                                              //
////////////////////////////////////////////////////////////////////////////////

/**
 * Is a given scale used by the instance of newmodule?
 *
 * This function returns if a scale is being used by one newmodule
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $newmoduleid ID of an instance of this module
 * @return bool true if the scale is used by the given newmodule instance
 */
function activitylist_scale_used($newmoduleid, $scaleid) {
    global $DB;

    /** @example */
    if ($scaleid and $DB->record_exists('activitylist', array('id' => $newmoduleid, 'grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if scale is being used by any instance of newmodule.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param $scaleid int
 * @return boolean true if the scale is used by any newmodule instance
 */
function activitylist_scale_used_anywhere($scaleid) {
    global $DB;

    /** @example */
    if ($scaleid and $DB->record_exists('activitylist', array('grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Creates or updates grade item for the give newmodule instance
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $newmodule instance object with extra cmidnumber and modname property
 * @return void
 */
function activitylist_grade_item_update(stdClass $newmodule) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    /** @example */
    $item = array();
    $item['itemname'] = clean_param($newmodule->name, PARAM_NOTAGS);
    $item['gradetype'] = GRADE_TYPE_VALUE;
    $item['grademax']  = $newmodule->grade;
    $item['grademin']  = 0;

    grade_update('mod/newmodule', $newmodule->course, 'mod', 'newmodule', $newmodule->id, 0, null, $item);
}

/**
 * Update newmodule grades in the gradebook
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $newmodule instance object with extra cmidnumber and modname property
 * @param int $userid update grade of specific user only, 0 means all participants
 * @return void
 */
function activitylist_update_grades(stdClass $newmodule, $userid = 0) {
    global $CFG, $DB;
    require_once($CFG->libdir.'/gradelib.php');

    /** @example */
    $grades = array(); // populate array of grade objects indexed by userid

    grade_update('mod/activitylist', $newmodule->course, 'mod', 'activitylist', $newmodule->id, 0, $grades);
}

////////////////////////////////////////////////////////////////////////////////
// File API                                                                   //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the lists of all browsable file areas within the given module context
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@link file_browser::get_file_info_context_module()}
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function activitylist_get_file_areas($course, $cm, $context) {
    return array();
}

/**
 * Serves the files from the newmodule file areas
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @return void this should never return to the caller
 */
function activitylist_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);

    send_file_not_found();
}

////////////////////////////////////////////////////////////////////////////////
// Navigation API                                                             //
////////////////////////////////////////////////////////////////////////////////

/**
 * Extends the global navigation tree by adding newmodule nodes if there is a relevant content
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $navref An object representing the navigation tree node of the newmodule module instance
 * @param stdClass $course
 * @param stdClass $module
 * @param cm_info $cm
 */
function activitylist_extend_navigation(navigation_node $navref, stdclass $course, stdclass $module, cm_info $cm) {
}

/**
 * Extends the settings navigation with the newmodule settings
 *
 * This function is called when the context for the page is a newmodule module. This is not called by AJAX
 * so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav {@link settings_navigation}
 * @param navigation_node $newmodulenode {@link navigation_node}
 */
function activitylist_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $newmodulenode=null) {
}


function activitylist_show($cm_id) {
    global $CFG;
    // capability check
    if ( !$cm = get_coursemodule_from_id('activitylist', $cm_id) ) {
        error("Course module ID was incorrect");
    }
    $activity_id = $cm->instance;

    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    require_capability('mod/activitylist:view_activities', $context);

    // define the MTTPATH which is used by the MyTinyTodylist code
    define("MTTPATH", dirname(__FILE__) . '/mtt/');

    require_once(MTTPATH . 'common.php');
    $_mtt_capabilities = mtt_get_capabilites($context); 

    // and include de index.php of MTT
    include MTTPATH . "/index.php";           
} // function activitylist_show


function activitylist_find_cmid_by_course_and_section($course_id, $section_id) {
    global $DB;
    if (! $cm = $DB->get_record_sql(
        "SELECT cm.* FROM {course_modules} AS cm, {modules} AS m 
         WHERE m.name = :module AND m.id = cm.module AND course = :course_id AND section = :section_id",
        array( 'course_id' => $course_id, 'module' => 'activitylist', 'section_id' => $section_id)
    )) return false;
    return $cm->id;
} // function activitylist_find_cmid_by_course_and_section
