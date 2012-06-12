<?php
include_once("{$CFG->dirroot}/local/soda/class.controller.php");
include_once("json_controller.php");
require_once(dirname(__FILE__) . '/../helpers/helper.group.php');

class file_controller extends json_controller {

    var $group_id = 0;

    function __construct($mod_name, $mod_instance_id) {
        parent::__construct($mod_name, $mod_instance_id);
        $this->require_login();
        /// testing
        $_SESSION['group_id'] = 1;
    } // function __construct
    
    
    protected function _already_exists($filename) {
        global $context;
        
        $parent_id = optional_param('parent_id', 0, PARAM_INT);
        
        $fs = get_file_storage();
        $fileinfo = (object)array(
            'contextid' => $context->id, // ID of context
            'component' => 'mod_source',     // usually = table name
            'filearea' => 'source',     // usually = table name
            'itemid' => helper_group::get_group_id(),  // usually = ID of row in table
            'filepath' => file::create_path(helper_group::get_group_id(), $parent_id),
            'filename' => $filename); // any filename
             
        $file = $fs->get_file($fileinfo->contextid, $fileinfo->component, $fileinfo->filearea, 
            $fileinfo->itemid, $fileinfo->filepath, $fileinfo->filename);

        return $file;
    }
    
    /**
     * checks if filename exists in folder; if so, add (1), (2), (3).. etc
     * 
     * @param mixed $filename
     * @return
     */
    protected function _make_unique_filename($filename) {
        $counter = 1;
        $filename_org = $filename;
        while ($this->_already_exists($filename)) {
            // if it exists add (1) or (2) etc
            $ext_pos = strrpos($filename, '.'); // last dot
            $filename = substr($filename_org, 0, $ext_pos) . "($counter)" . substr($filename_org, $ext_pos); 
            $counter++;
        }
        return $filename;
    }
    
    /**
     * Handles upload of multiple files
     * 
     * @return void
     */
    function add() {
        global $context;
        // we need a parent_id
        $parent_id = optional_param('parent_id', 0, PARAM_INT);
        
        // do we have files?
        if (isset($_FILES) && is_array($_FILES)) { 
            // walk all uploaded files
            foreach ($_FILES as $upload_name=>$file_record) {
                // only parse if upload name is file1, file2, file3, etc
                if (!preg_match('/file[0-9]+/', $upload_name)) { continue; }

                if (is_uploaded_file($file_record['tmp_name'])) {
                    
                    $file_record['name'] = $this->_make_unique_filename($file_record['name']);
                    
                    // see moodle file upload api
                    $fs = get_file_storage();
                    $fileinfo = array(
                        'contextid' => $context->id, // ID of context
                        'component' => 'mod_source',     // usually = table name
                        'filearea' => 'source',     // usually = table name
                        'itemid' => helper_group::get_group_id(),
                        'filepath' => file::create_path(helper_group::get_group_id(), $parent_id),
                        'filename' => $file_record['name']); // uploaded file name
                     
                    // Create file
                    $result = $fs->create_file_from_pathname($fileinfo, $file_record['tmp_name']);
                    
                    // internal moodle file id
                    $id = $result->get_id();

                    // get upload sequence number (file1, file2, file3, etc) --> 1, 2, 3
                    preg_match('/(\d+)/', $upload_name, $find_ints);
                    $upload_id = $find_ints[0];            
                    
                    // description1, description2, descrption3, etc
                    $description = optional_param('description' . $upload_id, '', PARAM_RAW);
                    $record = new file(array('files_id' => $id, 'group_id' => helper_group::get_group_id(), 
                        'path_id' => $parent_id, 'source_id' => (int)$this->source_id, 'description' => $description)); 
                    $record->save();
                    $record->update_search_document($id, $file_record['name'], $description);
                }
        
            }
        }
        if ($this->is_ajax()) {
            $this->return_json();
        }
        $this->redirect_to_url($this->get_url('controller=source&folder_id=' . (int)$parent_id)); 
    }
    
    function copy() {
        $files_id = required_param('file_id', PARAM_INT);
        $to_folder_id = required_param('thnk_source_copy_to_id', PARAM_INT);
        $file = file::load('files_id=?', false, array($files_id), -1);
        $file->copy_to($to_folder_id);
        $_REQUEST['parent_id'] = $to_folder_id;
        $this->return_json($to_folder_id);
        die();
    }
    
    function delete() {
        global $context, $DB;
        $folder_id = optional_param('parent_id', 0, PARAM_INT);
        $file_id = required_param('file_id', PARAM_INT);
        
        file::delete_record($file_id);
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            $this->return_json();
        }
        $this->redirect_to_url($this->get_url('controller=source&folder_id=' . (int)$folder_id)); 
    }

    function edit() {
        $file_id = required_param('file_id', PARAM_INT);
        $parent_id = optional_param('parent_id', 0, PARAM_INT);
        $file = file::load("files_id=?", false, array($file_id), $parent_id);
        
        $save = isset($_REQUEST['save']) || ($this->is_ajax() && isset($_REQUEST['filename'])); 
        if ($save) {
            $filename = required_param('filename', PARAM_FILE);
            $description = required_param('description', PARAM_RAW);
            $file->update($filename, $description);
        }
        if (isset($_REQUEST['save']) || isset($_REQUEST['cancel'])) {
            $this->redirect_to_url($this->get_url('controller=source&folder_id=' . (int)$parent_id)); 
        }
        if ($save && $this->is_ajax()) {
            $this->return_json();
        }
        $this->get_view(array('file' => $file), false, false);
    }
    

} // class course_controller 

?>
