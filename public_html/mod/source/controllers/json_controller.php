<?php

class json_controller extends controller {
    
    function return_json($parent_id=0) {
        global $CFG;
        $result = array();
        $elements = array();
        
        //ob_start();
        $parent_id = $parent_id ? $parent_id : optional_param('parent_id', 0, PARAM_INT);
        $_REQUEST['folder_id'] = intval($parent_id); // hack to return the correct files
        require_once(dirname(__FILE__) . '/../controllers/source.php');
        require_once(dirname(__FILE__) . '/../helpers/source/class.source_helper.php');
        require_once(dirname(__FILE__) . '/../models/source.php');
        $controller = new source_controller($this->mod_name, $this->source_id);
        $items = $controller->get_items();    
        include("{$CFG->dirroot}/mod/{$this->mod_name}/views/source/index.html");
        //$elements['thnk_source_files'] = ob_get_clean();
        die();
        
        $result['update_elements'] = $elements;
        
        die(json_encode($result));
    }
    
    function is_ajax() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ||
            isset($_REQUEST['ajax_call']);        
    }

}