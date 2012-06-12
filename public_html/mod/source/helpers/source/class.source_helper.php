<?php

class source_helper extends helper {

    function build_path($path_id) {
        global $DB;
        if ($path_id == 0) {
            return "";
        }
        $record = $DB->get_record_select('source_path', 'id=?', array($path_id));
        if ($record->parent_id) {
            return self::build_path($record->parent_id) . $record->path . '/'; 
        } else {
            return $record->path . '/';
        }
    }

    function getCrumbs() {
        $crumbs = array();
        $parent_id = isset($_REQUEST['folder_id']) ? (int)$_REQUEST['folder_id'] : 0;
        if (!$parent_id) {
            return $crumbs;
        }
        while ($parent_id != 0) {
            $folder = folder::load(sprintf('id=%d', $parent_id));
            $crumb = array(
                'url' => $this->get_url('folder_id=' . (int)$folder->id . '&controller=source'),
                'path' => $folder->path
            );
            $crumbs[] = $crumb;
            $parent_id = $folder->parent_id;
        }
        return array_reverse($crumbs);
    }
    
    function get_download_url($filename) {
        global $CFG, $context;
        return $CFG->wwwroot . sprintf("/pluginfile.php/%d/mod_source/file/%s/%s", 
            (int)$context->id, trim(file::create_path(), '/'), $filename);
    }
    
    function get_file_types() {
        static $types;
        if (!isset($types)) {
            $types_string = "mp3,wav,aif,aiff,ogg,aac,wma|Audio
                    flv,mov,avi,ram,mpg,mp4,rm,wmv,xvid,divx,mpeg|Video
                    doc,docx,dot,dotx|Microsoft Word
                    xls,xlsx|Microsoft Excel
                    swf|Flash
                    jpg,jpeg,gif,png,bmp,tif,tiff|Image
                    ppt,pptx|Microsoft Powerpoint
                    txt,rtf,readme,info|Text
                    xml|XML
                    zip,rar,sit,sitx,ace,7z|Archive
                    pdf|PDF
                    odt|OpenDocument Text
                    ods|OpenDocument Spreadsheet
                    odp|OpenDocument Presentation";
            foreach (explode("\n", $types_string) as $type_string) {
                list($exts, $description) =  array_map('trim', explode('|', $type_string));
                $types[$description] = array_map('trim', explode(',', $exts));
            }
        }
        return $types;
        
    }
    
    function get_file_description($filename) {
        $types = self::get_file_types();

        $ext = substr($filename, strrpos($filename, '.')); // find last dot
        foreach ($types as $description=>$exts) {
            if (in_array(strtolower(str_replace('.', '', $ext)), $exts)) {
                return $description;
            }
        }
        return "Document"; // default
    }
    
    function get_filesize($size_in_bytes) {
        $mod = 1024;
        
        $units = explode(' ','bytes KB MB GB TB PB');
        for ($i = 0; $size_in_bytes > $mod; $i++) {
            $size_in_bytes /= $mod;
        }
        
        return round($size_in_bytes, 2) . ' ' . $units[$i];
    }
    
    function get_filetype_filter() {
        $types = self::get_file_types();
        ksort($types);
        return $types;
    }
    
    function get_list_filename($file_item) {
        $filename = $file_item->filename;
        $search = isset($_REQUEST['thnk_source_search']) ? $_REQUEST['thnk_source_search'] : ''; 
        if ($search) {
            $path = self::build_path($file_item->path_id);
            $filename = $path . $filename;
        }
        return str_ireplace($search, "<strong>$search</strong>", $filename);
    }

    function get_list_foldername($folder_item) {
        $name = $folder_item->path;
        $search = isset($_REQUEST['thnk_source_search']) ? $_REQUEST['thnk_source_search'] : ''; 
        if ($search) {
            $path = self::build_path($folder_item->parent_id);
            $name = $path . $name;
        }
        return str_ireplace($search, "<strong>$search</strong>", $name);
    }

    function get_search_text() {
        $text = isset($_REQUEST['thnk_source_search']) ? htmlentities($_REQUEST['thnk_source_search']) : '';
        return $text; 
    }
} // class item_helper 

?>
