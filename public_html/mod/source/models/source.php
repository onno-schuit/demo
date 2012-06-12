<?php 

require_once("folder.php");
require_once("file.php");

class source_model extends model {
    
    public static function load_items($file_filters=array()) {
        $folder_id = isset($_REQUEST['folder_id']) ? intval($_REQUEST['folder_id']) : 0;
        
        $search = isset($_REQUEST['thnk_source_search']) ? str_replace("'", "", $_REQUEST['thnk_source_search']) : '';
        if ($search) {
            $folders = folder::load_all(sprintf("path LIKE '%%%s%%'", $search));
        } else {
            $folders = folder::load_all('parent_id=' . $folder_id);
        }
        foreach ($folders as $ix=>$folder) {
            $folders[$ix]->itemtype = 'folder';
        }
        
        if ($search) {
            $filter = sprintf("filename LIKE '%%%s%%'", str_replace("'", "", $_REQUEST['thnk_source_search']));
            $folder_id = -1;
        } else {
            $filter = '';
        }
        $files = file::load_all($filter, false, null, null, null, $folder_id);
        foreach ($files as $ix=>$file) {
            if (count($file_filters) && !in_array(end(explode('.', $file->filename)), $file_filters)) {
                // remove if file extension (end(explode(...))) is not in the file_filters array
                unset($files[$ix]);
            } else {
                $files[$ix]->itemtype = 'file';
            }
        }
        return array_merge($folders, $files);
    }
}

?>