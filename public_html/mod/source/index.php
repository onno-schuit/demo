<?php // $Id: index.php

require_once("../../config.php");
include_once('lib.php');


//$source_instance->display($no_layout = true, $activity_id = 3,  $overriding_controller = 'item', $overriding_action = 'index');
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' || isset($_REQUEST['ajax_call'])) {
    $source_instance->display($no_layout = true);
} else {
    $source_instance->display();
}
?>
