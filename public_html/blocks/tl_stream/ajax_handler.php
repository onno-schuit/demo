<?php

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot .'/course/lib.php');
require_once($CFG->libdir .'/filelib.php');
    
if (!isloggedin() || isguestuser()) die();

require_once("class.helper_moodle_system.php");

if (!isset($_REQUEST['_nonce']) || !helper_moodle_system::verifyNonce($_REQUEST['_nonce'], 'update_stream')) {
    // Nonce does not compute. Exit here..
    die();
}

require_once("class.tl_streams_controller.php");

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'none' && isset($_REQUEST['tlstream'])) {
    tl_streams_controller::store_user_preference($_REQUEST['tlstream']);
    die();
}
if (isset($_REQUEST['tl_stream_delete'])) {
    tl_streams_controller::delete_stream_item((int)$_REQUEST['tl_stream_delete']);
}

# handle direct stream messages (posted)
tl_streams_controller::handle_stream_messages();

# return items; ASCending order, because JS pushes every next item at the top of the div
$items = tl_streams_controller::get_items(
	array(
		'sort' => isset($_REQUEST['get_more']) ? 'DESC' : 'ASC',
        'limit_from' => isset($_REQUEST['get_more']) ? (int)$_REQUEST['get_more'] : 0, 
        'downloaded_ids' => isset($_REQUEST['downloaded_ids']) ? array_map('intval', $_REQUEST['downloaded_ids']) : array(),
        'limit' => 8,
        'stream' => 'system'  // always all stream items for ajax calls
	)
);
$output = array();
$meta = array();
$meta['_nonce'] = helper_moodle_system::getNonce('update_stream');
if (isset($_REQUEST['get_more'])) {
    $meta['_add_to'] = 'bottom';
    if (count($items) == 0) {
        $meta['_no_more_messages'] = 1;
    }
}
$output[] = array('meta' => $meta);

foreach ($items as $item) {
    # get the stream item handler (registered stream)
	$stream_handler = tl_streams_controller::get_stream_handler($item->short_code);
    
    # use output buffering for fetching per stream templates
    ob_start();
    include("tpl_stream_item.php");
    $html = ob_get_clean();

	# store output with metadata for json output    
    $output[] = array(
        'html' => $html,
        'meta' => array('id' => $item->id, 'is_personal' => $item->is_personal)
    );
}
#$output[] = array('meta' => array('sql', tl_streams_controller::$sql));

# send our data to the ajax client
echo json_encode($output);


?>