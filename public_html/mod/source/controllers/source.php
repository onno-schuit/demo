<?php
include_once("{$CFG->dirroot}/local/soda/class.controller.php");
include_once(dirname(__FILE__) . '/../helpers/source/class.source_helper.php');
require_once("folder.php");

class source_controller extends controller {

    var $group_id = 0;

    function __construct($mod_name, $mod_instance_id) {
        parent::__construct($mod_name, $mod_instance_id);
        $this->require_login();
        /// testing
        $_SESSION['group_id'] = 1;
    } // function __construct
    
    function get_items() {
        $exts = array();
        if (isset($_REQUEST['thnk_source_type']) && (!empty($_REQUEST['thnk_source_type']))) {
            $filetypes = source_helper::get_file_types();
            $exts = $filetypes[$_REQUEST['thnk_source_type']];
        }
        
        $items = source_model::load_items($exts);
        return $items;
    }
    
    function index() {

        $is_ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ||
            isset($_REQUEST['ajax_call']);
                
        $items = $this->get_items();    
        // see if we need filtering of filetypes
        $this->get_view(array('items' => $items, 'controller' => $this), false, !$is_ajax);
    }

} // class course_controller 

?>
