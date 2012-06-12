<?php

class helper_json {
    
    static function return_json($this) {
        global $CFG;
        $result = array();
        $elements = array();
        
        ob_start();
        $parent_id = intval(optional_param('parent_id', 0, PARAM_INT));
        $_REQUEST['folder_id'] = $parent_id; // hack to return the correct files
        require_once(dirname(__FILE__) . '/../controllers/source.php');
        require_once(dirname(__FILE__) . '/../models/source.php');
        $source_controller = new source_controller($this->mod_name, $this->source_id);
        $items = $source_controller->get_items();    
        include("{$CFG->dirroot}/mod/{$this->mod_name}/views/source/index_files.html");
        $elements['thnk_source_files'] = ob_get_clean();
        
        $result['update_elements'] = $elements;
        
        die(json_encode($result));
    }

}