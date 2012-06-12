<?php
include_once("{$CFG->dirroot}/local/soda/class.controller.php");

class connector_controller extends controller {


    function __construct($mod_name, $mod_instance_id, $action = false) {
        parent::__construct($mod_name, $mod_instance_id, $action = false);
        $this->require_login();
	global $PAGE;
	$PAGE->set_title("Creative sparks");
    } // function __construct


    function index() {
        echo "Okay from connector_controller#index";        
    } // function index

    function after_action() {
                
    } // function after_action



} // class connector_controller 
?>
