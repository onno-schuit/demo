<?php
//                ,-,------,
//              _ \(\(_,--'
//         <`--'\>/(/(__
//         /. .  `'` '  \
//        (`')  ,        @
//         `-._,        /
//            )-)_/--( >  jv
//           ''''  ''''
//  Sonsbeekmedia Block
//  Created by: Bas Brands
//  Contact: bmbrands@gmail.com
//  Date: 20 jan 2012
//
//  Description: Report Block

// Allows a teacher or non editing teacher to:
//
// View a questionnaire response report that contains:
//
// category ,CourseName, Questionnaire name, Studentname, Questionnaire submitted? *y/n*,
// Date submitted (nothing or date), Response recieved (yes/no if the submission has a comment)
// Response date (date the comment was posted, or latest one)
// if a student has submitted multiple submissions all will appear on table output


require('../../config.php');
require_once($CFG->dirroot.'/mod/questionnaire/lib.php');
require_once($CFG->dirroot . '/comment/lib.php');

require_login();
$instance = optional_param('instance', false, PARAM_INT);   // questionnaire ID
$action = optional_param('action', '', PARAM_TEXT);
$sitewide = optional_param('sitewide', false, PARAM_INT);

if ($action != 'csv') {
	$action = 'table';
}

if ($instance === false) {
	if (!empty($SESSION->instance)) {
		$instance = $SESSION->instance;
	} else {
		print_error('requiredparameter', 'questionnaire');
	}
}
$SESSION->instance = $instance;

if (! $questionnaire = $DB->get_record("questionnaire", array("id" => $instance))) {
	print_error('incorrectquestionnaire', 'questionnaire');
}
if (! $course = $DB->get_record("course", array("id" => $questionnaire->course))) {
	print_error('coursemisconf');
}
if (! $cm = get_coursemodule_from_instance("questionnaire", $questionnaire->id, $course->id)) {
	print_error('invalidcoursemodule');
}


$context = get_context_instance(CONTEXT_MODULE, $cm->id);
require_course_login($course, true, $cm);

$questionnaire = new questionnaire(0, $questionnaire, $course, $cm);


//if no capability to view comments, show error message
$usercanreadqcomments = has_capability('mod/questionnaire:readcomments',$context);
$usercanaddqcomments = has_capability('mod/questionnaire:addcomments',$context);
$canviewall = true;

//Structure data
// category ,CourseName, Questionnaire name, Studentname, Questionnaire submitted? y/n,
// Date submitted (nothing or date), Response recieved (yes/no if the submission has a comment)
// Response date (date the comment was posted, or latest one)

$tabledata[] = array(
get_string('category'),
get_string('course'),
get_string('questionnaire','block_questionnaire_report'),
get_string('studentname','block_questionnaire_report'),
get_string('submitted','block_questionnaire_report'),
get_string('submitdate','block_questionnaire_report'),
get_string('comment','block_questionnaire_report'),
get_string('commentdate','block_questionnaire_report'));

if ($sitewide && $canviewall) {
	$questionnaires_sql = "SELECT * FROM ".$CFG->prefix."questionnaire";
	$questionnaires = $DB->get_records_sql($questionnaires_sql);
	foreach ($questionnaires as $questionnaire) {
		$course = $DB->get_record("course", array("id" => $questionnaire->course));
		$cm = get_coursemodule_from_instance("questionnaire", $questionnaire->id, $course->id);
		$context = get_context_instance(CONTEXT_MODULE, $cm->id);
		$questionnaire = new questionnaire(0, $questionnaire, $course, $cm);
		$tabledata = block_questionnaire_collect_data($context,$questionnaire,$course);
	}
} else {
	$tabledata = block_questionnaire_collect_data($context,$questionnaire,$course);
}


switch ($action) {

	case 'table':
		$PAGE->set_course($COURSE);
		$PAGE->set_url('/blocks/questionnaire_report/report.php');
		$PAGE->set_heading($SITE->fullname);
		$PAGE->set_pagelayout('course');
		$PAGE->set_title(get_string('questionnaire_report', 'block_questionnaire_report'));
		$PAGE->navbar->add(get_string('questionnaire_report', 'block_questionnaire_report'));



		if (empty($usercanaddqcomments)) {
			$notificationerror = get_string('cannotreadquestionnaire_report', 'block_questionnaire_report');
		}
		if (!empty($notificationerror)) {
			echo $OUTPUT->header();
			echo $OUTPUT->heading(get_string('error', 'block_questionnaire_report'), 'block_questionnaire_report');
			echo $OUTPUT->notification($notificationerror);
			echo $OUTPUT->footer();
			die();
		}

		// OUTPUT
		echo $OUTPUT->header();
		echo $OUTPUT->heading(get_string('questionnaire_report', 'block_questionnaire_report'), 3, 'main');
		if ($canviewall) {
			echo '<a href="'. $CFG->wwwroot . '/blocks/questionnaire_report/report.php?instance=' . $instance . '&sitewide=1">';
			echo get_string('viewsitewide','block_questionnaire_report');
			echo '</a><br>';
		}

		echo (get_string('downloadtext'));
		echo $OUTPUT->box_start();
		echo "<form action=\"{$CFG->wwwroot}/blocks/questionnaire_report/report.php\" method=\"GET\">\n";
		echo "<input type=\"hidden\" name=\"instance\" value=\"$instance\" />\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"csv\" />\n";
		echo (get_string('options','block_questionnaire_report'));
		echo "<br />\n";
		if ($canviewall) {
			echo html_writer::checkbox('sitewide', 1, true, get_string('sitewide', 'block_questionnaire_report'));
		}
		echo "<br />\n";
		echo "<br />\n";
		echo "<input type=\"submit\" name=\"submit\" value=\"".get_string('download', 'block_questionnaire_report')."\" />\n";
		echo $OUTPUT->help_icon('downloadtextformat','block_questionnaire_report');
		echo "</form>\n";
		echo $OUTPUT->box_end();
		echo "<br />\n";


		block_questionnaire_report_table($tabledata);
		echo $OUTPUT->footer();
		break;

	case 'csv':
		$filename = str_replace(' ', '_', $questionnaire->name);
		$filename .= clean_filename('-' . gmdate("Ymd_Hi"));
		$filename .= '.csv';

		header("Content-Type: application/download\n");
		header("Content-Disposition: attachment; filename=$filename");
		header('Expires: 0');
		header('Cache-Control: must-revalidate,post-check=0,pre-check=0');
		header('Pragma: public');

		echo block_questionnaire_report_csv($tabledata);

		break;

}
function block_questionnaire_collect_data($context,$questionnaire,$course) {
	global $CFG, $DB, $tabledata, $action;
	//Get database records
	$sid = $questionnaire->survey->id;
	$sql_responses = "SELECT id,username,submitted,complete FROM ".$CFG->prefix."questionnaire_response R WHERE survey_id=".$sid." ORDER BY username";
	$responses = $DB->get_records_sql($sql_responses);

	$sql_comments = "SELECT id,userid,timecreated,itemid,contextid FROM ".$CFG->prefix."comments WHERE contextid=".$context->id." ORDER BY userid";
	$comments = $DB->get_records_sql($sql_comments);

	if (!has_capability('mod/questionnaire:addcomments',$context)) {
		return $tabledata;
	}

	$category = $DB->get_record('course_categories', array('id' => $course->category));


	$users = get_enrolled_users($context);
	foreach ($users as $user) {
		$complete ='';
		$foundresponse = false;

		foreach ($responses as $response) {
			if ($response->username == $user->id){
				$foundresponse = true;
					
				if ($action == 'table') {
					$complete = '<a href="' .$CFG->wwwroot . '/mod/questionnaire/report.php?action=vresp&sid='.$sid.'&rid=' . $response->id .'">';
					$complete .= $response->complete;
					$complete .= '</a>';
				} else {
					$complete = $response->complete;
				}

				$date =  userdate($response->submitted);
					
				foreach ($comments as $comment) {
					if ($comment->itemid == $response->id) {
						$commentsreceived = 'y';
						$commentdate =  userdate($comment->timecreated);
					}
				}
				$tabledata[] = array($category->name, $course->fullname,
				               $questionnaire->name, $user->firstname . ' ' .  $user->lastname,
				               $complete, $date,$commentsreceived,$commentdate);
			}
		}

		if (!$foundresponse) {
			$complete = 'n';
			$date = '';
			$commentsreceived = '';
			$commentdate = '';
			$tabledata[] = array($category->name, $course->fullname,
			               $questionnaire->name, $user->firstname . ' ' .  $user->lastname,
			               $complete, $date,$commentsreceived,$commentdate);
		}


	}
	return $tabledata;
}

function block_questionnaire_report_csv($tabledata) {
	$delimiter = ';';
	$returnstr = '';
	foreach ($tabledata as $tablerow) {

		$returnstr .= implode($delimiter, $tablerow) . "\n";
	}
	return $returnstr;
}

function block_questionnaire_report_table($tabledata) {
	$countrows = 0;
	$t = new html_table();
	foreach ($tabledata as $tablerow) {
		$rows[$countrows] = new html_table_row();
		foreach ($tablerow as $cell) {
			$rows[$countrows]->cells[] = $cell;
		}
		$countrows++;

	}
	$t->data = $rows;
	echo html_writer::tag('div', html_writer::table($t), array('class'=>'no-overflow'));
}