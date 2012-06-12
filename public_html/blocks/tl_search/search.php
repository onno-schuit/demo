<?php
/**
 * Search results page.
 *
 * @copyright &copy; 2007 The Open University
 * @author s.marshall@open.ac.uk
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package oublog
 *//** */
// This code tells OU authentication system to let the public access this page
// (subject to Moodle restrictions below and with the accompanying .sams file).
global $DISABLESAMS;
$DISABLESAMS = 'opt';
require_once('../../config.php');
require_once($CFG->dirroot.'/local/ousearch/searchlib.php');
require_once($CFG->libdir.'/adminlib.php');
require_once("class.search_helper.php");

#admin_externalpage_setup('tl_stream_kinds'); // see blocks/tl_stream/settings.php: admin_externalpage('tl_stream_kinds'...

$querytext = required_param('query',PARAM_RAW);
$querytexthtml = htmlspecialchars($querytext);

$context = get_context_instance(CONTEXT_SYSTEM);
$PAGE->set_context($context);
$PAGE->set_title(format_string('test')); // get_string('results_page_title', 'block_tl_search'));
$PAGE->set_url('/blocks/tl_search/search.php');
$PAGE->set_pagelayout('frontpage');
$PAGE->navbar->add(get_string('search', 'block_tl_search'));

#$PAGE->navbar->add(get_string('searchfor','local_ousearch',$querytext));
#$PAGE->set_button($buttontext);
//$CFG->additionalhtmlhead .= oublog_get_meta_tags($oublog, $oubloginstance, $currentgroup, $cm);
echo $OUTPUT->header();

global $modulecontext,$personalblog;
#$modulecontext=$context;

// FINALLY do the actual query
$query=new local_ousearch_search($querytext);
$query->set_course_ids(search_helper::get_courses_from_users($USER->id));
if (!isloggedin()) {
    $query->set_plugin('mod_ouwiki');
}

#$query->set_coursemodule($cm);
#if($groupmode && $currentgroup) {
#    $query->set_group_id($currentgroup);
#}
#$query->set_filter('visibility_filter');

// get groups this user is a member of
global $DB, $USER;
$results = $DB->get_records_select('groups_members', 'userid=?', array($USER->id));
$ids = array(); // something non existent
foreach ($results as $result) {
    $ids[] = (int)$result->groupid;
}
if ($ids) {
    $query->set_group_ids($ids, $or_none=true);
}

$searchurl = $CFG->wwwroot . '/blocks/tl_search/search.php';

$foundsomething=$query->display_results($searchurl);

if(!$foundsomething) {
#    add_to_log($COURSE->id,'oublog','view searchfailure',
#        $searchurl.'&query='.urlencode($querytext));
}
echo $foundsomething;

//Add link to search the rest of this website if service available
/*
if (!empty($CFG->block_resources_search_baseurl)) {
    $params = array('course' => $course->id, 'query' => $querytext);
    $restofwebsiteurl = new moodle_url('/blocks/resources_search/search.php', $params);
    $strrestofwebsite = get_string('restofwebsite', 'local_ousearch');
    $altlink = html_writer::start_tag('div', array('class' => 'advanced-search-link'));
    $altlink .= html_writer::link($restofwebsiteurl, $strrestofwebsite);
    $altlink .= html_writer::end_tag('div');
    print $altlink;
}
*/
// Footer
echo $OUTPUT->footer();

/**
 * Function filters search results to exclude ones that don't meet the
 * visibility criterion.
 *
 * @param object $result Search result data
 */
function visibility_filter(&$result) {
    global $USER,$modulecontext,$personalblog;
    return oublog_can_view_post($result->data,$USER,$modulecontext,$personalblog);
}
