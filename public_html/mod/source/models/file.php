<?php 

require_once(dirname(__FILE__) . '/../helpers/helper.group.php');

class file extends model {

    static $table_name = 'source_file';
    
    public static function load($where_clause = false, $include = false, $params = null, $folder_id=0) {
        if (!$objects = static::load_all($where_clause, $include, $params, $limitfrom = -1, $limitnum = 1, $folder_id)) return false;
        return array_shift($objects);
    } // function load

    public static function load_all($where_clause = false, $include = false, $params = null, $limitfrom = null, $limitnum = null, $folder_id=0) {
        global $CFG, $DB;
        $wheres = array(1);
        if ($where_clause) {
            $wheres[] = $where_clause;
        }
        if ($folder_id >= 0) {
            $wheres[] = sprintf("sf.path_id=%d", $folder_id);
        } 
        $wheres[] = sprintf("sf.group_id=%d", helper_group::get_group_id());

        $where = "WHERE " . join(' AND ', $wheres);
        
        if (! $recordset = $DB->get_recordset_sql($sql = "SELECT *
                                                   FROM {$CFG->prefix}" . static::table_name() . " sf
                                                   INNER JOIN {files} f ON (f.id=sf.files_id)
                                                   $where 
                                                   ORDER BY f.filename",
                                                   $params,
                                                   $limitfrom, $limitnum) ) return;
        ///die($sql);
        $objects = static::convert_to_objects($recordset);
        $recordset->close();
        if ($include) $objects = static::load_associations($objects, $include);
        return $objects;       
    }
    
    public static function delete_record($file_id) {
        global $context, $DB;
        $file_record = $DB->get_record_sql("SELECT f.*, sf.path_id 
            FROM {files} f 
            INNER JOIN {source_file} sf ON (f.id=sf.files_id)
            WHERE f.id=?", array($file_id));
        
        $fs = get_file_storage();
        $fileinfo = (object)array(
            'contextid' => $context->id, // ID of context
            'component' => 'mod_source',     // usually = table name
            'filearea' => 'source',     // usually = table name
            'itemid' => helper_group::get_group_id(),  // usually = ID of row in table
            'filepath' => self::create_path(helper_group::get_group_id(), $file_record->path_id),
            'filename' => $file_record->filename); // any filename
             
        $file = $fs->get_file($fileinfo->contextid, $fileinfo->component, $fileinfo->filearea, 
            $fileinfo->itemid, $fileinfo->filepath, $fileinfo->filename);

        if ($file) {
            $file->delete();
        }
        
        $DB->delete_records_select("source_file", "files_id=?", array($file_id)); 
    }
    
    public function copy_to($folder_id) {
        global $context, $DB;
        $file_record = $DB->get_record_sql("SELECT f.*, sf.path_id 
            FROM {files} f 
            INNER JOIN {source_file} sf ON (f.id=sf.files_id)
            WHERE f.id=?", array($this->files_id));
        
        $fs = get_file_storage();
        $fileinfo = (object)array(
            'contextid' => $context->id, // ID of context
            'component' => 'mod_source',     // usually = table name
            'filearea' => 'source',     // usually = table name
            'itemid' => helper_group::get_group_id(),  // usually = ID of row in table
            'filepath' => self::create_path(helper_group::get_group_id(), $file_record->path_id),
            'filename' => $file_record->filename); // any filename
             
        $file = $fs->get_file($fileinfo->contextid, $fileinfo->component, $fileinfo->filearea, 
            $fileinfo->itemid, $fileinfo->filepath, $fileinfo->filename);
            
        if (!$file) {
            die('error: File not found');
        }

        // check if new filename already exists
        $filename = $fileinfo->filename;
        $fileinfo->filepath = self::create_path(helper_group::get_group_id(), $folder_id);
        $i = 1;
        while ($fs->get_file($fileinfo->contextid, $fileinfo->component, $fileinfo->filearea, 
        $fileinfo->itemid, $fileinfo->filepath, $fileinfo->filename)) {
            // if it exists add (1) or (2) etc
            $ext_pos = strrpos($filename, '.');
            $fileinfo->filename = substr($filename, 0, $ext_pos) . "($i)" . substr($filename, $ext_pos);  
            $i++;
        }
        $newfile = $fs->create_file_from_storedfile($fileinfo, $file);
        
        // update the {source_file} record with the new moodle file id
        $record = (object)array(
            'path_id' => $folder_id,
            'files_id' => $newfile->get_id(),
            'group_id' => $this->group_id,
            'description' => $this->description
        );
        $DB->insert_record('source_file', $record);
    }
    
    /**
     * Returns "/$group_id/$path_id/" for path field in de Moodle {files} table
     * 
     * @param mixed $group_id
     * @param mixed $path_id
     * @return string
     */
    public static function create_path($group_id=false, $path_id=false) {
        $path = '/';
        $path .= $group_id !== false ? (int)$group_id : helper_group::get_group_id();
        $path .= '/';
        $path .= $path_id !== false ? (int)$path_id : (isset($_REQUEST['folder_id']) ? (int)$_REQUEST['folder_id'] : 0);
        $path .= '/';
        return $path;
    }
    
    public function update($filename, $description) {
        
        if ($filename == '') {
            die('error: File name cannot be empty');
        }
        global $DB, $context;
        
        // load {source_file} data from db
        $record = $DB->get_record_select(self::$table_name, 'files_id=?', array($this->id));

        if ($filename != $this->filename) {
            
            // filename changed: copy the file internally (Moodle files api)
            $fs = get_file_storage();
            $fileinfo = (object)array(
                'contextid' => $context->id, // ID of context
                'component' => 'mod_source',     // usually = table name
                'filearea' => 'source',     // usually = table name
                'itemid' => helper_group::get_group_id(),  // usually = ID of row in table
                'filepath' => self::create_path(helper_group::get_group_id(), $record->path_id),
                'filename' => $this->filename); // any filename
                 
            $file = $fs->get_file($fileinfo->contextid, $fileinfo->component, $fileinfo->filearea, 
                $fileinfo->itemid, $fileinfo->filepath, $fileinfo->filename);
                
            if (!$file) {
                die('error: File not found');
            }
            
            // check if new filename already exists
            $newfileinfo = $fileinfo;
            $newfileinfo->filename = $filename;
            if ($fs->get_file($fileinfo->contextid, $fileinfo->component, $fileinfo->filearea, 
                $fileinfo->itemid, $fileinfo->filepath, $newfileinfo->filename)) {
                    die('error: File with that name already exists');
                }
            $newfile = $fs->create_file_from_storedfile($newfileinfo, $file);
            
            // update the {source_file} record with the new moodle file id
            $record->files_id = $newfile->get_id();
            
            // delete the old moodle file record
            $file->delete(); // old file
            $filename = $newfileinfo->filename;
        }

        // update descriptio        
        $record->description = $description;
        // write record to db
        $DB->update_record(self::$table_name, $record);
        $this->update_search_document($record->files_id, $filename, $description);
    }
    
    public function update_search_document($id, $filename, $description) {
        global $USER, $cm;
        if (defined('OUSEARCH_FOUND')) {
            $post = (object)array(
                'id' => $id,
                'userid' => $USER->id,
                'filename' => $filename,
                'description' => $description
            );
            oussource_file::update($post, $cm);
        }
    }
    
}

?>