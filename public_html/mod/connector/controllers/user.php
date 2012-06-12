<?php
include_once("{$CFG->dirroot}/mod/connector/controllers/connector.php");
include_once("{$CFG->dirroot}/mod/connector/models/message_contact.php");
include_once("{$CFG->dirroot}/mod/connector/models/user.php");

class user_controller extends connector_controller {
    function index() {
        global $course, $cm;
        $user_filters = isset($_POST['filters']) ? $_POST['filters'] : array();
	$filters = array();
	if(isset($_REQUEST['role']) && $_REQUEST['role']!='')
		$filters = array_merge($user_filters, array('role' => $_REQUEST['role']));
	else if(!isset($_REQUEST['role']))
		$filters = array_merge($user_filters, array('role' => 'expert'));

        echo $this->get_view(array(
                'users' => connector_user::filter_by($this->user, $filters),
                'filters' => $filters,
                'contacts' => message_contact::load_all("userid = {$this->user->id}"),
                'new_contacters' => connector_user::userids_with_new_messages_for($this->user)));
    } // function index






    function test() {
        echo $this->get_view();
    } // function test
} // class user_controller 
?>
