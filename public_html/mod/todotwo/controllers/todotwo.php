<?php
include_once("{$CFG->dirroot}/local/soda/class.controller.php");
include_once("{$CFG->dirroot}/mod/todotwo/models/group.php");
require_once($CFG->dirroot . '/blocks/mycollaborator/lib.php');

class todotwo_controller extends controller {

    var $group_id = 0;
    var $group = false;

    function __construct($mod_name, $mod_instance_id) {
        global $course, $cm;
        parent::__construct($mod_name, $mod_instance_id);
        $this->require_login();
        $this->group_id = groups_get_activity_group($cm);
        $this->group = new group(array('id' => $this->group_id));
    } // function __construct


    function index() {
        $this->no_layout = true;
        $this->redirect_to_url( $this->get_url("controller=item") );
    } // function index

} // class todotwo_controller 

?>
