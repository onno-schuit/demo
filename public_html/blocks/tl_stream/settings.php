<?php

defined('MOODLE_INTERNAL') || die;


include_once('class.admin_setting_url.php');
include_once('class.tl_stream_manage.php');

# adds button to navigation, below Sitemanagement -> modules -> blocks
$ADMIN->add('blocksettings', new admin_externalpage('tl_stream_kinds', 'Manage stream kinds', $CFG->wwwroot . '/blocks/tl_stream/admin_stream_kinds.php'));

$settings->add(new admin_setting_url('stream_kinds', 
    get_string('manage_stream_kinds', 'block_tl_stream'), 
    $CFG->wwwroot . '/blocks/tl_stream/admin_stream_kinds.php')
);


# get stream kinds from database
$stream_kinds = tl_stream_manage::get_kinds();

/*
$settings->add(new admin_setting_heading('tl_stream_guests', 'Display streams for guests', ''));
# display checkboxes for enabling or disabling streams for guests
foreach ($stream_kinds as $kind) {
    $settings->add(new admin_setting_configcheckbox('block_tl_stream/guest_' . $kind->short_code, 
        $kind->description, '', 0)); 
}
*/

$settings->add(new admin_setting_heading('tl_stream_users', 'Display streams for students', ''));
# display checkboxes for enabling or disabling streams for students (or other logged on users)
foreach ($stream_kinds as $kind) {
    $settings->add(new admin_setting_configcheckbox('block_tl_stream/user_' . $kind->short_code, 
        $kind->description, '', 1)); 
}


