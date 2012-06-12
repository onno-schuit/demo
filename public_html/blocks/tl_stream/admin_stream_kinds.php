<?php 


require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('class.tl_stream_manage.php');

// Moodle needs to know where to put this screen in the navi menu
admin_externalpage_setup('tl_stream_kinds'); // see blocks/tl_stream/settings.php: admin_externalpage('tl_stream_kinds'...
// display Moodle theme header (and navi menu)  
echo $OUTPUT->header();

// some validation before passing it to the update method
$post_data = array('records' => array());
if (isset($_POST['tl_stream_config']) && is_array($_POST['tl_stream_config'])) {
    $post_data['records'] = $_POST['tl_stream_config'];
}
if (isset($_POST['tl_new_stream_config']) && is_array($_POST['tl_new_stream_config'])) {
    $post_data['new_record'] = $_POST['tl_new_stream_config'];
} else {
    $post_data['new_record'] = array('short_code' => '', 'description' => '');
}
if (isset($_POST['tl_stream_delete']) && is_array($_POST['tl_stream_delete'])) {
    $post_data['delete'] = $_POST['tl_stream_delete'];
}
// update records
if (!empty($post_data)) {
    tl_stream_manage::update($post_data);
}

// display the form with records and all
tl_stream_manage::display();

echo $OUTPUT->footer();


?>