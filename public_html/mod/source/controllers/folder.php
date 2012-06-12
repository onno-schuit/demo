<?php
include_once("{$CFG->dirroot}/local/soda/class.controller.php");
include_once("json_controller.php");
require_once(dirname(__FILE__) . '/../helpers/helper.group.php');

class folder_controller extends json_controller {

    var $group_id = 0;

    function __construct($mod_name, $mod_instance_id) {
        parent::__construct($mod_name, $mod_instance_id);
        $this->require_login();
        /// testing
        $_SESSION['group_id'] = 1;
    } // function __construct
    
    
    function add() {
        $folder_name = required_param('folder_name', PARAM_RAW);
        $parent_id = optional_param('parent_id', 0, PARAM_INT);
        $existing = folder::load('parent_id=? AND path=?', false, array($parent_id, $folder_name));
        if (isset($existing->id)) {
            die("error: folder with that name already exists");
        }
        $record = new folder(array('path' => $folder_name, 'parent_id' => $parent_id, 
            'source_id' => $this->source_id, 'group_id' => helper_group::get_group_id()));
        $record->save();

        if ($this->is_ajax()) {
            $this->return_json();
        }
        $this->redirect_to_url($this->get_url('controller=source&folder_id=' . (int)$parent_id)); 
    }

    function delete() {
        global $context, $DB;
        $parent_id = optional_param('parent_id', 0, PARAM_INT);
        $folder_id = required_param('folder_id', PARAM_INT);
        
        folder::delete_record($folder_id);
        
        if ($this->is_ajax()) {
            $this->return_json();
        }
        $this->redirect_to_url($this->get_url('controller=source&folder_id=' . (int)$parent_id)); 
    }
    
    function edit() {
        $folder_id = required_param('folder_id', PARAM_INT);
        $parent_id = optional_param('parent_id', 0, PARAM_INT);
        $folder = folder::load("id=?", false, array($folder_id));
        $path = optional_param('path', '', PARAM_RAW);
        $existing = folder::load('parent_id=? AND path=?', false, array($parent_id, $path));
        if (isset($existing->id)) {
            die("error: folder with that name already exists");
        }
        
        $save = isset($_REQUEST['save']) || ($this->is_ajax() && isset($_REQUEST['path'])); 
        if ($save) {
            $folder->path = $_REQUEST['path'];
            $folder->save_without_validation();
        }
        if (isset($_REQUEST['save']) || isset($_REQUEST['cancel'])) {
            $this->redirect_to_url($this->get_url('controller=source&folder_id=' . (int)$parent_id)); 
        }
        if ($save && $this->is_ajax()) {
            $this->return_json();
        }
        
        $this->get_view(array('folder' => $folder), false, !$this->is_ajax());
    }

} // class course_controller 

?>
