<?php
// $Id: format.php,v 1.29 2011/10/06 00:55:38 gb2048 Exp $
/**
 * Collapsed Topics Information
 *
 * @package    course/format
 * @subpackage topcoll
 * @copyright  2009-2011 @ G J Barnard in respect to modifications of standard topics format.
 * @link       http://docs.moodle.org/en/Collapsed_Topics_course_format
 * @license    http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)
 */

// Display the whole course as "topics" made of of modules
// Included from "view.php"
// Initially modified from format.php in standard topics format.
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/filelib.php');
require_once($CFG->libdir.'/completionlib.php');
require_once("{$CFG->dirroot}/mod/activitylist/lib.php");
require_once("{$CFG->dirroot}/mod/todotwo/lib.php");
require_once("{$CFG->dirroot}/mod/source/lib.php");

// Now get the css and JavaScript Lib.  The call to topcoll_init sets things up for JavaScript to work by understanding the particulars of this course.
?>
<style type="text/css" media="screen">
/* <![CDATA[ */
@import
	url(<?php echo $CFG->wwwroot ?>/course/format/topcoll/topics_collapsed.css)
	;
/* ]]> */
</style>
<!--[if lte IE 7]>
    <link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot ?>/course/format/topcoll/ie-7-hacks.css" media="screen" />
<![endif]-->
<?php
$PAGE->requires->js('/course/format/topcoll/lib.js');
$PAGE->requires->js_function_call('topcoll_init',
array($CFG->wwwroot,
preg_replace("/[^A-Za-z0-9]/", "", $SITE->shortname),
$course->id,
null)); // Expiring Cookie Initialisation - replace 'null' with your chosen duration.
if (ajaxenabled() && $PAGE->user_is_editing()) {
	// This overrides the 'swap_with_section' function in /lib/ajax/section_classes.js
	$PAGE->requires->js('/course/format/topcoll/tc_section_classes_min.js');
}

require_once($CFG->dirroot . '/blocks/mycollaborator/lib.php');
$currentgroup = mycollaborator_get_active_group($course->id);
$topic = optional_param('ctopics', -1, PARAM_INT);
$_SESSION['group_id'] = $currentgroup;


if ($topic != -1) {
	$displaysection = course_set_display($course->id, $topic);
} else {
	$displaysection = course_get_display($course->id); // MDL-23939
}

$context = get_context_instance(CONTEXT_COURSE, $course->id);

if (($marker >=0) && has_capability('moodle/course:setcurrentsection', $context) && confirm_sesskey()) {
	$course->marker = $marker;
	$DB->set_field("course", "marker", $marker, array("id"=>$course->id));
}

$streditsummary  = get_string('editsummary');
$stradd          = get_string('add');
$stractivities   = get_string('activities');
$strshowalltopics = get_string('showalltopics');
$strtopic         = get_string('topic');
$strgroups       = get_string('groups');
$strgroupmy      = get_string('groupmy');
$editing         = $PAGE->user_is_editing();

if ($editing) {
	$strtopichide = get_string('hidetopicfromothers');
	$strtopicshow = get_string('showtopicfromothers');
	$strmarkthistopic = get_string('markthistopic');
	$strmarkedthistopic = get_string('markedthistopic');
	$strmoveup   = get_string('moveup');
	$strmovedown = get_string('movedown');
}

// Print the Your progress icon if the track completion is enabled
$completioninfo = new completion_info($course);
echo $completioninfo->display_help_icon(); // MDL-25927

//echo $OUTPUT->heading(get_string('topicoutline'), 2, 'headingblock header outline');


// If currently moving a file then show the current clipboard
if (ismoving($course->id)) {
	$stractivityclipboard = strip_tags(get_string('activityclipboard', '', $USER->activitycopyname));
	$strcancel= get_string('cancel');
	echo '<div class="clipboard">';
	echo '<div colspan="3">';
	echo $stractivityclipboard.'&nbsp;&nbsp;(<a href="mod.php?cancelcopy=true&amp;sesskey='.$USER->sesskey.'">'.$strcancel.'</a>)';
	echo '</div>';
	echo '</div>';
}

// Print Section 0 with general activities
$section = 0;
$thissection = $sections[$section];
unset($sections[0]);

/*
if ($thissection->summary or $thissection->sequence or $PAGE->user_is_editing()) {
	echo '<div id="section-0" class="section main">';
	echo '<div class="left side">&nbsp;</div>';
	echo '<div class="content">';

	if (!is_null($thissection->name)) { // MDL-29188
		echo $OUTPUT->heading(format_string($thissection->name, true, array('context' => $context)), 3, 'sectionname');
	}

	echo '<div class="summary">';

	$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
	$summarytext = file_rewrite_pluginfile_urls($thissection->summary, 'pluginfile.php', $coursecontext->id, 'course','section', $thissection->id);
	$summaryformatoptions = new stdClass();
	$summaryformatoptions->noclean = true;
	$summaryformatoptions->overflowdiv = true;
	echo format_text($summarytext, $thissection->summaryformat, $summaryformatoptions);
	
	echo "Current Group: " . $currentgroup;

	if ($PAGE->user_is_editing() && has_capability('moodle/course:update', $coursecontext)) {
		echo '<a title="'.$streditsummary.'" '.
                 ' href="editsection.php?id='.$thissection->id.'"><img src="'.$OUTPUT->pix_url('t/edit') . '" '.
                 ' class="icon edit" alt="'.$streditsummary.'" /></a>';
	}
	echo '</div>';

	print_section($course, $thissection, $mods, $modnamesused);

	if ($PAGE->user_is_editing()) {
		print_section_add_menus($course, $section, $modnames);
	}

	echo '</div>';
	echo '</div>';
	echo '<div class="section separator"><div colspan="3" class="spacer"></div></div>';
}
*/

// Get the specific words from the language files.
$topictext = get_string('sectionname','format_topcoll'); // This is defined in lang/en of the formats installation directory - basically, the word 'Toggle'.
$toggletext = get_string('topcolltoggle','format_topcoll'); // The table row of the toggle.

// Toggle all.
/*
 echo '<div id="toggle-all" class="section main">';
 echo '<div class="left side toggle-all" colspan="2">';
 echo '<h4><a class="on" href="#" onclick="all_opened(); return false;">'.get_string('topcollopened','format_topcoll').'</a><a class="off" href="#" onclick="all_closed(); return false;">'.get_string('topcollclosed','format_topcoll').'</a>'.get_string('topcollall','format_topcoll').'</h4>';
 echo '</div>';
 echo '<div class="right side">&nbsp;</div>';
 echo '</div>';
 echo '<div class="section separator"><div colspan="3" class="spacer"></div></div>';
 */

// Now all the normal modules by topic
// Everything below uses "section" terminology - each "section" is a topic.
$section = 1;
$sectionmenu = array();

while ($section <= $course->numsections) {
	if (!empty($sections[$section])) {
		$thissection = $sections[$section];
	} else {
		$thissection = new stdClass;
		$thissection->course  = $course->id;   // Create a new section structure
		$thissection->section = $section;
		$thissection->name    = null;
		$thissection->summary  = '';
		$thissection->summaryformat = FORMAT_HTML;
		$thissection->visible  = 1;
		$thissection->id = $DB->insert_record('course_sections', $thissection);
	}

	$showsection = (has_capability('moodle/course:viewhiddensections', $context) or $thissection->visible or !$course->hiddensections);

	if (!empty($displaysection) and $displaysection != $section) { // Check this topic is visible
		if ($showsection) {
			$sectionmenu[$section] = get_section_name($course, $thissection);
		}
		$section++;
		continue;
	}

	if ($showsection) {
		$currenttopic = ($course->marker == $section);

		$currenttext = '';
		if (!$thissection->visible) {
			$sectionstyle = ' hidden';
		} else if ($currenttopic) {
			$sectionstyle = ' current';
			$currenttext = get_accesshide(get_string('currenttopic','access'));
		} else {
			$sectionstyle = '';
		}

		
		echo '<div class="thnksectionwrapper">';
		echo '<div class="cps" id="sectionhead-'.$section.'">';
		// Have a different look depending on if the section summary has been completed.
		if (is_null($thissection->name)) {
			echo '<div class="sectionheadcontent"><a id="sectionatag-'.$section.'" class="cps_nosumm" href="#" onclick="toggle_topic(this,'.$section.'); return false;">'.$topictext.' '.$currenttext.$section.'</a>';
		} else {
			echo '<div class="sectionheadcontent"><a id="sectionatag-'.$section.'" class="section_names" href="#" onclick="toggle_topic(this,'.$section.'); return false;">'.html_to_text(format_string($thissection->name, true, array('context' => $context))).'</a>';  // format_string from MDL-29188
			// Comment out the above line and uncomment the line below if you do not want 'Topic x' displayed on the right hand side of the toggle.
			//echo '<div colspan="3"><a id="sectionatag-'.$section.'" href="#" onclick="toggle_topic(this,'.$section.'); return false;"><span>'.html_to_text(format_string($thissection->name, true, array('context' => $context))).'</span> - '.$toggletext.'</a></div>';
		}
       
		if ($PAGE->user_is_editing() && has_capability('moodle/course:update', $context)) {
		    echo '<div id="thnkcoursecontrols">';
		    if ($course->marker == $section) { // Show the "light globe" on/off
		        echo '<a href="view.php?id='.$course->id.'&amp;marker=0&amp;sesskey='.sesskey().'#section-'.$section.'" title="'.$strmarkedthistopic.'">'.'<img src="'.$OUTPUT->pix_url('i/marked') . '" alt="'.$strmarkedthistopic.'" /></a>';
		    } else {
		        echo '<a href="view.php?id='.$course->id.'&amp;marker='.$section.'&amp;sesskey='.sesskey().'#section-'.$section.'" title="'.$strmarkthistopic.'">'.'<img src="'.$OUTPUT->pix_url('i/marker') . '" alt="'.$strmarkthistopic.'" /></a>';
		    }

		    if ($thissection->visible) { // Show the hide/show eye
		        echo '<a href="view.php?id='.$course->id.'&amp;hide='.$section.'&amp;sesskey='.sesskey().'#section-'.$section.'" title="'.$strtopichide.'">'.
                         '<img src="'.$OUTPUT->pix_url('i/hide') . '" class="icon hide" alt="'.$strtopichide.'" /></a>';
		    } else {
		        echo '<a href="view.php?id='.$course->id.'&amp;show='.$section.'&amp;sesskey='.sesskey().'#section-'.$section.'" title="'.$strtopicshow.'">'.
                         '<img src="'.$OUTPUT->pix_url('i/show') . '" class="icon hide" alt="'.$strtopicshow.'" /></a>';
		    }
		    if ($section > 1) { // Add a arrow to move section up
		        echo '<a href="view.php?id='.$course->id.'&amp;random='.rand(1,10000).'&amp;section='.$section.'&amp;move=-1&amp;sesskey='.sesskey().'#section-'.($section-1).'" title="'.$strmoveup.'">'.
                         '<img src="'.$OUTPUT->pix_url('t/up') . '" class="icon up" alt="'.$strmoveup.'" /></a>';
		    }

		    if ($section < $course->numsections) { // Add a arrow to move section down
		        echo '<a href="view.php?id='.$course->id.'&amp;random='.rand(1,10000).'&amp;section='.$section.'&amp;move=1&amp;sesskey='.sesskey().'#section-'.($section+1).'" title="'.$strmovedown.'">'.
                         '<img src="'.$OUTPUT->pix_url('t/down') . '" class="icon down" alt="'.$strmovedown.'" /></a>';
		    }
		    echo '</div>';
		}

		echo '</div></div>';

		// Now the section itself.  The css class of 'hid' contains the display attribute that manipulated by the JavaScript to show and hide the section.  It is defined in js-override-topcoll.css which
		// is loaded into the DOM by the JavaScript function topcoll_init.  Therefore having a logical separation between static and JavaScript manipulated css.  Nothing else here differs from
		// the standard Topics format in the core distribution.  The next change is at the bottom.
		echo '<div id="section-'.$section.'" class="thkcoursesec section main'.$sectionstyle.'" style="display:none;">';

		/*
		 echo '<div class="left side">'.$currenttext.$section.'</div>';
		 */
		
		// Comment out the above line and uncomment the line below if you do not want the section number displayed on the left hand side of the section.
		//echo '<div class="left side">&nbsp;</div>';

		echo '<div class="content">';
		if (!has_capability('moodle/course:viewhiddensections', $context) and !$thissection->visible) {   // Hidden for students
			//echo get_string('notavailable');
		} else {
			echo '<div class="summary">';
			if ($thissection->summary) {
				$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
				$summarytext = file_rewrite_pluginfile_urls($thissection->summary, 'pluginfile.php', $coursecontext->id, 'course','section', $thissection->id);
				$summaryformatoptions = new stdClass();
				$summaryformatoptions->noclean = true;
				$summaryformatoptions->overflowdiv = true;
				echo format_text($summarytext, $thissection->summaryformat, $summaryformatoptions);
			}

			if ($PAGE->user_is_editing() && has_capability('moodle/course:update', $context)) {
				echo '<a title="'.$streditsummary.'" href="editsection.php?id='.$thissection->id.'">'.
                         '<img src="'.$OUTPUT->pix_url('t/edit') . '" class="icon edit" alt="'.$streditsummary.'" /></a><br /><br />';
			}
			echo '</div>';
            
			switch($section) {
				
				case 1:
                    //echo $currentgroup;
					if (! $cm = todotwo::find_cm_by_course_and_section($course->id, $thissection->id) ) {
						echo $OUTPUT->notification('Could not find an activity list here');
						break;
					}
					$todotwo_id = $cm->instance;
					$todotwo_instance = new todotwo();
					$todotwo_instance->display($no_layout = true, $todotwo_id, $overriding_controller = 'item', $overriding_action = 'index');
					break;
                case 2:
					if (! $cm = source::find_cm_by_course_and_section($course->id, $thissection->id) ) {
						echo $OUTPUT->notification('Could not find sources module here');
						break;
					}
					$source_id = $cm->instance;
					$source_instance = new source();
					$source_instance->display($no_layout = true, $source_id);
                    global $source_instance_id;
                    $source_instance_id = $cm->id;
                    break;
				case 3:
					if ($forum = forum_get_course_section_forum($course->id, 'social', $thissection->section)) {

						$cm = get_coursemodule_from_instance('forum', $forum->id);
						$context = get_context_instance(CONTEXT_MODULE, $cm->id);

						//groups_print_activity_menu($cm, $CFG->wwwroot . '/mod/forum/view.php?id=' . $cm->id);
						
						$currentgroup = groups_get_activity_group($cm);
						$groupmode = groups_get_activity_groupmode($cm);

						/// Print forum intro above posts  MDL-18483
						if (trim($forum->intro) != '') {
							$options = new stdClass();
							$options->para = false;
							$introcontent = format_module_intro('forum', $forum, $cm->id);

							if ($PAGE->user_is_editing() && has_capability('moodle/course:update', $context)) {
								$streditsummary  = get_string('editsummary');
								$introcontent .= '<div class="editinglink"><a title="'.$streditsummary.'" '.
                                             '   href="modedit.php?update='.$cm->id.'&amp;sesskey='.sesskey().'">'.
                                             '<img src="'.$OUTPUT->pix_url('t/edit') . '" '.
                                             ' class="icon edit" alt="'.$streditsummary.'" /></a></div>';
							}
							echo $OUTPUT->box($introcontent, 'generalbox', 'intro');
						}

						echo '<div class="subscribelink">', forum_get_subscribe_link($forum, $context), '</div>';
						forum_print_latest_discussions($course, $forum, 10, 'plain', '', false);

					} else {
						echo $OUTPUT->notification('Could not find or create a social forum here');
					}
					break;

			}
			

			print_section($course, $thissection, $mods, $modnamesused);

			if ($PAGE->user_is_editing()) {
				print_section_add_menus($course, $section, $modnames);
			}
		}
		echo '</div>';



		echo '</div></div>';

		echo '<div class="section separator"><div colspan="3" class="spacer"></div></div>';
	}

	unset($sections[$section]);
	$section++;
}

if (!$displaysection and $PAGE->user_is_editing() and has_capability('moodle/course:update', $context)) {
	// print stealth sections if present
	$modinfo = get_fast_modinfo($course);
	foreach ($sections as $section=>$thissection) {
		if (empty($modinfo->sections[$section])) {
			continue;
		}

		echo '<div id="section-'.$section.'" class="section main clearfix orphaned hidden">';
		echo '<div class="content">';
		echo $OUTPUT->heading(get_string('orphanedactivities'), 3, 'sectionname');
		print_section($course, $thissection, $mods, $modnamesused);
		echo '</div>';
		echo "</div>\n";
	}
}


if (!empty($sectionmenu)) {
	$select = new single_select(new moodle_url('/course/view.php', array('id'=>$course->id)), 'ctopics', $sectionmenu);
	$select->label = get_string('jumpto');
	$select->class = 'jumpmenu';
	$select->formid = 'sectionmenu';
	echo $OUTPUT->render($select);
}

// Establish persistance when we have loaded.
// Reload the state of the toggles from the data contained within the cookie.
// Restore the state of the toggles from the cookie if not in 'Show topic x' mode, otherwise show that topic.
if ($displaysection == 0) {
	echo $PAGE->requires->js_function_call('reload_toggles',array($course->numsections));
} else {
	echo $PAGE->requires->js_function_call('show_topic',array($displaysection));
}
