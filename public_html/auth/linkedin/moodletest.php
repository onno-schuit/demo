<?php
require_once(dirname(__FILE__)) . '/../../config.php';
$PAGE->set_context(get_context_instance(CONTEXT_SYSTEM));
$PAGE->set_url('/auth/linkedin/moodletest.php');
echo $OUTPUT->header();

echo "<p>Input string : 4EDvjBs8GS</p>";

$test_string = "4EDvjBs8GS";
$username = clean_param($test_string, PARAM_ALPHA);

echo "<p>Output string: " . $username . "</p>";

if ($user = $DB->get_record('user', array('username' => $username, 'deleted' => 0, 'mnethostid' => $CFG->mnet_localhost_id))) {
   echo "<p>Found user:" .$user->id ."</p>" ;
}

echo $OUTPUT->footer();
?>